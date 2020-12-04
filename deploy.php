<?php
namespace Deployer;

require 'recipe/symfony.php';

// Project name
set('application', 'mediaport');

// Project repository
set('repository', 'https://github.com/gcilinskas/stream.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);


host('master')
    ->hostname('194.31.52.21')
    ->user('root')
    ->forwardAgent()
    ->set('project_dir_name', 'mediaport')
    ->set('deploy_path', '/var/www/{{project_dir_name}}')
    ->set('virtual_hosts_dir', '/var/www/vhosts')
    ->set('virtual_host_path', '{{virtual_hosts_dir}}/{{project_dir_name}}')
    ->set('web_server_public_path', '{{virtual_host_path}}/public_html')
    ->set('branch', 'master')
    ->set('keep_releases', 2)
    ->set('local_env', 'prod');

/**
 * Main dev task
 */
task(
    'deploy:master',
    [
        'deploy:info',
        'deploy:prepare',
        'deploy:lock',
        'deploy:release',
        'deploy:update_code',
        'deploy:shared',
        'deploy:writable',
        'deploy:update_envs',
        'deploy:vendors',
        'build:assets',
        'database:migrate',
        'deploy:cache:clear',
        'deploy:cache:warmup',
        'deploy:symlink',
        'deploy:unlock',
        'deploy:public_symlink',
        'cleanup',
    ]
)->desc('Deploy to mediaport.lt');
// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

