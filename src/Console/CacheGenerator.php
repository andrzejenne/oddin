<?php


namespace BigBIT\Oddin\Console;


use BigBIT\Oddin\Traits\InjectsOnDemand;
use BigBIT\Oddin\Utils\CacheResolver;
use BigBIT\Oddin\Utils\ClassMapResolver;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


// adapters
use Symfony\Component\Cache\Adapter\ApcuAdapter as Apcu;
use Symfony\Component\Cache\Adapter\ArrayAdapter as ArrayCache;
use Symfony\Component\Cache\Adapter\DoctrineAdapter as Doctrine;
use Symfony\Component\Cache\Adapter\FilesystemAdapter as Filesystem;
use Symfony\Component\Cache\Adapter\MemcachedAdapter as Memcached;
use Symfony\Component\Cache\Adapter\PdoAdapter as Pdo;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter as PhpArray;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter as PhpFiles;
use Symfony\Component\Cache\Adapter\RedisAdapter as Redis;

/**
 * Class CacheGenerator
 * @package BigBIT\Oddin\Console
 * @property ClassMapResolver $classMapResolver
 * @property CacheResolver $cacheResolver
 */
class CacheGenerator extends Command
{
    use InjectsOnDemand;

    protected static $defaultName = 'cache:injectables:create';

    protected static $predefinedAdapters = [
        'apcu' => Apcu::class,
        'array' => ArrayCache::class,
        'doctrine' => Doctrine::class,
        'filesystem' => Filesystem::class,
        'memcached' => Memcached::class,
        'pdo' => Pdo::class,
        'php' => PhpArray::class,
        'php-files' => PhpFiles::class,
        'redis' => Redis::class,

    ];

    protected function configure()
    {
        $this->setDescription('Creates an ODDIN injectables cache.')
            ->setHelp('This command helps you create ODDIN injectables cache')
            ->addArgument('type', InputArgument::REQUIRED, 'Cache type')
            ->addOption('args', 'a', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'Cache type arguments');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cacheType = $input->getArgument('type');
        $ui = new SymfonyStyle($input, $output);

        if (isset(static::$predefinedAdapters[$cacheType])) {
            $adapterClass = static::$predefinedAdapters[$cacheType];
        }
        else {
            if (class_exists($cacheType)) {
                $adapterClass = $cacheType;
            }
            else {
                $ui->error("Invalid type $cacheType");

                return;
            }
        }

        try {
            $args = $input->getOption('args');
            $cache = new Psr16Cache(new $adapterClass(...$args));
        }
        catch (\Exception $exception) {
            $ui->error("Cannot initialize cache");
            $ui->comment($exception->getMessage());

            return;
        }

        $this->cacheResolver->setCache($cache);

        $loader = $this->classMapResolver->getClassLoader();

        foreach ($loader->getClassMap() as $path) {
            try {
                require_once $path;
            }
            catch (\Throwable $throwable) {

            }
        }

        $declared = get_declared_classes();


        foreach ($declared as $cls) {
            try {
                $injectables = $this->cacheResolver->getInjectables($cls);
            }
            catch (\Exception $exception) {

            }
        }

        $this->cacheResolver->shutDown();
    }
}