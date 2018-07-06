<?php

use Boedah\Robo\Task\Drush\DrushStack;
use Drupal\Component\Utility\NestedArray;
use Robo\Exception\TaskException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class RoboFile extends \Robo\Tasks {

  /**
   * Constant variable environment.
   */
  const ENV_DEV = 'dev';
  const ENV_STAGE = 'stage';
  const ENV_PROD = 'prod';

  /**
   * Options arguments for command line.
   */
  const OPTS = [
    'site|s' => 'default',
    'environment|e' => self::ENV_DEV,
  ];

  /**
   * Directory backups and others.
   */
  const FOLDER_PROPERTIES = 'build';
  const FOLDER_TEMPLATES = 'build/templates';
  const FOLDER_BACKUPS_DATABASE = 'build/backups';

  /**
   * Twig Environment.
   *
   * @var \Twig_Environment
   */
  protected $twig;

  /**
   * Store properties used.
   *
   * @var array
   */
  protected $properties = [];

  /**
   * Store environment used.
   *
   * @var string
   */
  protected $environment = 'dev';

  /**
   * Store site name used.
   *
   * @var string
   */
  var $site = 'default';

  /**
   * Store if use default site or not.
   *
   * @var bool
   */
  var $use_default = TRUE;

  /**
   * Which environments are considered dev.
   *
   * @var array
   */
  var $env_dev = [self::ENV_DEV, self::ENV_STAGE];

  /**
   * Build a site from configuration files.
   *
   * @param array $opts Options.
   *   Options.
   *
   * @option $environment|e Environment
   * @option $site|s Site
   *
   * @throws \Robo\Exception\TaskException
   */
  public function buildConf($opts = self::OPTS) {
    // Init parameters.
    $this->init($opts['environment'], $opts['site']);

    // Backups.
    $this->backupDatabase();

    // Setup for installation.
    $this->setupInstallation();

    // Install.
    $this->install();

    // Configuration files.
    $this->configureSettings();
    $this->createBuildServicesYaml();

    // Import configurations.
    $this->importConfig();

    // Import contents.
    $this->defaultContentImport();

    // Protect installation.
    $this->protectSite();

    // Run cron, entity update.
    $this->coreCron();
    $this->entityUpdates();

    // Rebuild cache.
    $this->rebuildCache();

    // Show info site.
    $this->getInfoSite();
  }

  /**
   * Export configuration after clear cache.
   *
   * @param array $opts Options.
   *   Options.
   *
   * @option $environment|e Environment
   * @option $site|s Site
   *
   * @throws \Robo\Exception\TaskException
   */
  public function configurationExport($opts = self::OPTS) {
    // Init parameters.
    $this->init($opts['environment'], $opts['site']);

    $this->rebuildCache();
    $this->exportConfig();
  }

  /**
   * Import configuration.
   *
   * @param array $opts Options.
   *   Options.
   *
   * @option $environment|e Environment
   * @option $site|s Site
   *
   * @throws \Robo\Exception\TaskException
   */
  public function configurationImport($opts = self::OPTS) {
    // Init parameters.
    $this->init($opts['environment'], $opts['site']);

    $this->importConfig();
    $this->rebuildCache();
  }

  /**
   * Import content/fixtures.
   *
   * @param array $opts
   *
   * @throws \Robo\Exception\TaskException
   */
  public function contentImport($opts = self::OPTS) {
    // Init parameters.
    $this->init($opts['environment'], $opts['site']);

    $this->defaultContentImport();
  }

  /**
   * Export content/fixtures.
   *
   * @param array $opts
   *
   * @throws \Robo\Exception\TaskException
   */
  public function contentExport($opts = self::OPTS) {
    // Init parameters.
    $this->init($opts['environment'], $opts['site']);

    $this->defaultContentExport();
  }

  /*
   * == PRIVATE FUNCTIONS ==.
   */

  /**
   * Init.
   *
   * @param string $environment
   *   Environment variable (dev|stage|prod|custom1|..).
   * @param string $site
   *   Match to the sites that you want to start, useful for multi-site.
   *   In the case of a single installation to use/leave 'default'.
   *
   * @throws \Robo\Exception\TaskException
   */
  private function init($environment = 'dev', $site = 'default') {

    if ($site == 'default' && !$this->use_default) {
      throw new TaskException($this, 'Default site not implement. Please select the site installation.');
    }

    // Set Environment and site for load configuration file.
    $this->setEnvironment($environment);
    $this->setSite($site);
    $this->properties = $this->getProperties();

    // Load Twig and Filesystem.
    $loader = new Twig_Loader_Filesystem($this->getBasePath() . '/' . self::FOLDER_TEMPLATES);
    $this->twig = new Twig_Environment($loader);
  }

  /**
   * Print info site.
   *
   * @throws \Robo\Exception\TaskException
   */
  private function getInfoSite() {
    $this->say('Site Drupal Info');
    $this->getDrushWithUri()->status()->run();
  }

  /**
   * Setup file and directory for installation.
   *
   * Include:
   *  - clear file and directory site;
   *  - init file and directory site.
   */
  private function setupInstallation() {
    $this->clearFilesystem();
    $this->initFilesystem();
  }

  /**
   * Install Drupal.
   *
   * @param array $properties_custom
   *   Properties custom. es. $properties['site_configuration']['profile'].
   *
   * @throws \Robo\Exception\TaskException
   */
  private function install($properties_custom = []) {
    $this->say('Install Drupal');
    $properties = $this->properties;
    // User replace and not merge because merge duplicate entry with same key.
    // @see http://php.net/manual/en/function.array-replace-recursive.php
    $properties = array_replace_recursive($properties, $properties_custom);
    $this->getDrush()
      ->siteName($properties['site_configuration']['name'])
      ->siteMail($properties['site_configuration']['mail'])
      ->accountMail($properties['account']['mail'])
      ->accountName($properties['account']['name'])
      ->accountPass($properties['account']['pass'])
      ->dbUrl($properties['databases']['default']['url'])
      ->locale($properties['site_configuration']['locale'])
      ->sitesSubdir($properties['site_configuration']['sub_dir'])
      ->siteInstall($properties['site_configuration']['profile'])
      ->run();
  }

  /**
   * Clear Cache.
   *
   * @throws \Robo\Exception\TaskException
   */
  private function rebuildCache() {
    $this->say('Rebuild cache');
    $this->getDrushWithUri()
      ->drush('cache-rebuild')
      ->run();
  }

  /**
   * Execute Core cron.
   *
   * @throws \Robo\Exception\TaskException
   */
  private function coreCron() {
    $this->say('Core cron');
    $this->getDrushWithUri()
      ->drush('core-cron')
      ->run();
  }

  /**
   * Clears the directory structure for site.
   */
  private function clearFilesystem() {
    $this->say('Clears the directory structure for site');
    $site_path = "{$this->getSiteRoot()}/sites/{$this->properties['site_configuration']['sub_dir']}";
    $this->taskFilesystemStack()
      ->chmod($site_path, 0775, 0000, TRUE)
      ->chmod($site_path, 0775)
      ->remove($site_path . '/files')
      ->remove($site_path . '/settings.php')
      ->remove($site_path . '/services.yml')
      // Custom files.
      ->remove($site_path . '/build.services.yml')
      ->run();

    $base_path = "{$this->getSiteRoot()}/sites";
    $this->taskFilesystemStack()
      ->remove($base_path . '/sites.php')
      ->run();
  }

  /**
   * Creates the directory structure for site.
   */
  private function initFilesystem() {
    $this->say('Creates the directory structure for site');
    $base_path = "{$this->getSiteRoot()}/sites/{$this->properties['site_configuration']['sub_dir']}";
    $this->taskFilesystemStack()
      ->chmod($base_path, 0775, 0000, TRUE)
      ->mkdir($base_path . '/files')
      ->mkdir($base_path . '/files/logs')
      ->chmod($base_path . '/files', 0775, 0000, TRUE)
      ->copy($base_path . '/default.settings.php', $base_path . '/settings.php')
      ->copy($base_path . '/default.services.yml', $base_path . '/services.yml')
      ->run();
  }

  /**
   * Setup correct permission for settings.php.
   *
   * @TODO: update permission.
   */
  private function protectSite() {
    $base_path = "{$this->getSiteRoot()}/sites/{$this->properties['site_configuration']['sub_dir']}";
    $this->say('Protect settings.php');
    $this->taskFilesystemStack()
      ->chmod($base_path . '/default.settings.php', 0755)
      ->chmod($base_path . '/settings.php', 0755)
      ->chmod($base_path . '/default.services.yml', 0755)
      ->chmod($base_path . '/services.yml', 0755)
      ->chmod($base_path, 0775)
      ->run();
  }

  /**
   * Create file YAML services custom.
   */
  private function createBuildServicesYaml() {
    $this->say('Create Build YAML Services');

    $base_path = "{$this->getSiteRoot()}/sites/{$this->properties['site_configuration']['sub_dir']}";
    $build_file_path = $base_path . "/build.services.yml";
    $template_name = "build.services.{$this->environment}.{$this->site}.twig";
    $build = $this->templateRender($template_name, $this->getProperties());

    $this->taskFilesystemStack()->chmod($base_path, 0777)->run();

    $task_write = $this->taskWriteToFile($build_file_path);
    $task_write->line($build)->append()->run();
  }

  /**
   * Configure settings.
   *
   * Using templates based on the name of the site and the environment,
   * update the settings file.
   */
  private function configureSettings() {
    $this->say('Configure settings');

    $settings_file_path = "{$this->getSiteRoot()}/sites/{$this->properties['site_configuration']['sub_dir']}/settings.php";

    $this->taskFilesystemStack()->chmod($settings_file_path, 0777)->run();

    // Get configurations databases.
    $databases = $this->getProperties()['databases'];
    $settings = [];

    foreach ($databases as $database_key => $database_info) {
      $settings['databases'][$database_key] = $this->convertDatabaseFromDatabaseUrl($database_info);

      // Insert manual the info of 'prefix' and 'namespace' because is not found in url.
      $settings['databases'][$database_key]['prefix'] = $database_info['prefix'];
      $settings['databases'][$database_key]['namespace'] = $database_info['namespace'];
    }

    // Merge new configuration.
    $variables = NestedArray::mergeDeepArray([
      $this->getProperties(),
      $settings,
    ], TRUE);

    $template_name = "settings.{$this->environment}.{$this->site}.twig";
    $local_settings = $this->templateRender($template_name, $variables);

    $task_write = $this->taskWriteToFile($settings_file_path);
    $task_write->line($local_settings)->append()->run();
  }

  /**
   * Import config.
   *
   * Import configurations from folder defined in the configuration file yml.
   *
   * @throws \Robo\Exception\TaskException
   */
  private function importConfig() {
    $this->say('Import config');

    // This task refer to $config_directories[CONFIG_SYNC_DIRECTORY].
    $this->getDrushWithUri()
      ->drush('cim')
      ->run();
  }

  /**
   * Export configuration.
   *
   * Export configurations in the folder defined in the configuration file yml.
   *
   * @throws \Robo\Exception\TaskException
   */
  private function exportConfig() {
    $this->say('Export config');

    // This task refer to $config_directories[CONFIG_SYNC_DIRECTORY].
    $this->getDrushWithUri()
      ->drush('cex')
      ->run();
  }

  /**
   * Backup database.
   *
   * The folder destination is configured in FOLDER_BACKUPS_DATABASE.
   *
   * @param null|string $path
   *   Path for dump.
   *
   * @throws \Robo\Exception\TaskException
   */
  private function backupDatabase($path = NULL) {

    if (!$this->isSiteInstalled()) {
      $this->say("Backup Database not execute. Site not installed.");
      return;
    }

    $folder_backups = $this->getBasePath() . "/" . self::FOLDER_BACKUPS_DATABASE;
    $path_dump = $folder_backups;
    if (isset($path)) {
      // TODO: check path.
      $path_dump = $path;
    }

    $this->say('Backup database.');
    $database_name = date("Y") . date("m") . date("d") . '_' . date("H") . date("i") . date("s") . '.sql';

    $this->getDrushWithUri()
      ->drush("sql-dump --result-file={$path_dump}/{$this->properties['site_configuration']['uri']}_{$database_name} --ordered-dump")
      ->run();
  }

  /**
   * Update entity.
   *
   * @throws \Robo\Exception\TaskException
   */
  private function entityUpdates() {
    $this->say('Entity Update');
    $this->getDrushWithUri()
      ->drush('entity-updates')
      ->run();
  }

  /**
   * Default content deploy import.
   *
   * @throws \Robo\Exception\TaskException
   */
  private function defaultContentImport() {
    $this->say('Default content deploy: import');
    $this->getDrushWithUri()
      ->drush('default-content-deploy:import')
      ->run();
  }

  /**
   * Default content deploy export.
   *
   * @throws \Robo\Exception\TaskException
   */
  private function defaultContentExport() {
    $this->say('Default content deploy: export');
    $this->getDrushWithUri()
      ->drush('default-content-deploy:export-with-references node')
      ->run();
  }

  /**
   * Retrieve a DrushStack.
   *
   * @return \Boedah\Robo\Task\Drush\DrushStack
   *   Retrieve an object DrushStack with the root folder set.
   */
  private function getDrush() {
    /** @var \Boedah\Robo\Task\Drush\DrushStack $drush_stack */
    $drush_stack = $this->task(DrushStack::class, $this->properties['drush_path']);
    $drush_stack->drupalRootDirectory("{$this->getBasePath()}/{$this->properties['site_configuration']['root']}");
    return $drush_stack;
  }

  /**
   * Retrieve a DrushStack with the URI configuration set.
   *
   * @return \Boedah\Robo\Task\Drush\DrushStack
   *   Retrieve an object DrushStack with the root folder and URI sets.
   */
  private function getDrushWithUri() {
    return $this->getDrush()
      ->uri($this->properties['site_configuration']['uri']);
  }

  /**
   * Get Base Path (path absolute files).
   *
   * @return string
   *   Path absolute files.
   */
  private function getBasePath() {
    return $this->properties['base_path'];
  }

  /**
   * Get Site Path (path absolute files).
   *
   * @return string
   *   Path absolute files of root installation.
   */
  private function getSiteRoot() {
    return "{$this->getBasePath()}/{$this->properties['site_configuration']['site_root']}";
  }

  /**
   * Retrieve if site is installed. Check exist settings.php.
   *
   * @return bool
   *   True if site is installed.
   */
  private function isSiteInstalled() {
    $filesystem = new Filesystem();
    return $filesystem->exists("{$this->getSiteRoot()}/sites/{$this->properties['site_configuration']['sub_dir']}/settings.php");
  }

  /**
   * Renders a template.
   *
   * @param string $template
   *   Template.
   * @param array $variables
   *   Variables.
   *
   * @return string
   *   Template rendered.
   */
  private function templateRender($template, $variables) {
    return $this->twig->render($template, $variables);
  }

  /**
   * Set environment.
   *
   * @param string $environment
   *   Environment.
   */
  private function setEnvironment($environment = 'dev') {
    $this->environment = $environment;
  }

  /**
   * Set Site.
   *
   * @param string $site
   *   Site.
   */
  private function setSite($site = 'default') {
    $this->site = $site;
  }

  /**
   * Get Properties.
   *
   * @return array
   *   Properties.
   */
  private function getProperties() {
    $file_name = "build.{$this->environment}.{$this->site}.yml";
    $base_path = "./" . self::FOLDER_PROPERTIES;
    $file_content = file_get_contents("$base_path/$file_name");
    $properties = Yaml::parse($file_content);

    return $properties;
  }

  /**
   * Convert from an old-style database URL to an array of database settings.
   *
   * @param array database_info
   *   A Drupal 6 db url string to convert, or an array with a 'default' element.
   *
   * @return array
   *   An array of database values containing only the 'default' element of
   *   the db url. If the parse fails the array is empty.
   */
  private function convertDatabaseFromDatabaseUrl($database_info) {
    $db_url = $database_info['url'];
    $db_spec = [];

    if (is_array($db_url)) {
      $db_url_default = $db_url['default'];
    }
    else {
      $db_url_default = $db_url;
    }

    // If it's a sqlite database, pick the database path and we're done.
    if (strpos($db_url_default, 'sqlite://') === 0) {
      $db_spec = [
        'driver' => 'sqlite',
        'database' => substr($db_url_default, strlen('sqlite://')),
      ];
    }
    else {
      $url = parse_url($db_url_default);
      if ($url) {
        // Fill in defaults to prevent notices.
        $url += [
          'scheme' => NULL,
          'user' => NULL,
          'pass' => NULL,
          'host' => NULL,
          'port' => NULL,
          'path' => NULL,
          'prefix' => '',
          'namespace' => '',
        ];
        $url = (object) array_map('urldecode', $url);
        $db_spec = [
          'driver' => $url->scheme == 'mysqli' ? 'mysql' : $url->scheme,
          'username' => $url->user,
          'password' => $url->pass,
          'host' => $url->host,
          'port' => $url->port,
          'database' => ltrim($url->path, '/'),
          'prefix' => $url->prefix,
          'namespace' => $url->namespace,
        ];
      }
    }

    return $db_spec;
  }

}
