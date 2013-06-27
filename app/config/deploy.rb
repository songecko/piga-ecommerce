# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL

## SERVER CONFIGURATION ##

set :application,        "Pigalle"
set :domain,             "pigalle.com.ar"
set :deploy_to,          "/var/www/vhosts/pigalle.com.ar/httpdocs"
set :user,               "pigalle"
set :password,           "HUcszb5R"
set :app_path,           "app"
set :web_path,           "web"
set :use_sudo,            false

## ROLES ##

role :web,                domain
role :app,                domain
role :db,                 domain, :primary => true

## SOURCE CONFIGURATION ##

set :scm,                 :git
set :repository,          "https://github.com/songecko/piga-ecommerce.git"
set :deploy_via,          :copy #:capifony_copy_local #(for the first release)
set :copy_dir, 	          "deploy_temp"

## VENDORS ##

set :use_composer,        false ## true, on first release 
set :use_composer_tmp,    true 
set :composer_options,    "--verbose"
set :update_vendors,      false
set :dump_assetic_assets, true

## PERMISSIONS ##

set :writable_dirs,       [app_path+"/cache", app_path+"/logs"]
set :webserver_user,      "www-data"
set :permission_method,   :chmod

## SHARED FILES ##

set :shared_files,        ["app/config/parameters.yml", "web/.htaccess", "web/robots.txt"]
set :shared_children,     [app_path+"/logs", web_path+"/media"]

## MISC ##

set :clear_controllers,   false
set :model_manager,       "doctrine"
set :keep_releases,       3


## TASKS ##

namespace :symfony do
	namespace :assets do
		desc "Remember install assets manually"
		task :install do
			capifony_pretty_print "--> Remember install assets manually"
		end
	end
	namespace :cache do
		desc "Remember warmup the cache"
		task :warmup do
			capifony_pretty_print "--> Remember warmup the cache"
		end
	end
	namespace :assetic do
		desc "Remember dump assetic"
		task :dump do
			capifony_pretty_print "--> Remember dump assetic"
		end
	end
#	namespace :bootstrap do
#		desc "Build bootstrap.php.cache"
#		task :build do
#			capifony_pretty_print "--> Building bootstrap file"			
#
#       		set :build_bootstrap, "vendor/sensio/distribution-bundle/Sensio/Bundle/DistributionBundle/Resources/bin/build_bootstrap.php"
#      		run "#{try_sudo} sh -c 'cd #{latest_release} && test -f #{build_bootstrap} && #{php_bin} #{build_bootstrap} #{app_path} || echo '#{build_bootstrap} not found, skipped''"
#		end
#	end	
#	TODO: ASK IF FIRST RELEASE OR NOT (TO INSTALL VENDORS OR NOT)
#	namespace :vendor do
#		desc "Copy vendors from previous release"
#  			task :copy_from_previous, :except => { :no_release => true } do
#    			if Capistrano::CLI.ui.agree("Do you want to copy last release vendor dir then do composer install ?: (y/N)")
#      			capifony_pretty_print "--> Copying vendors from previous release"
#	  
#      			run "cp -a #{previous_release}/vendor #{latest_release}/"
#      			capifony_puts_ok
#    		end
#  		end
#	end
end


## TASK LISTENERS ##

before "deploy:finalize_update", "symfony:composer:copy_vendors" ## DISABLE ON THE FIRST RELEASE
after "symfony:composer:copy_vendors", "symfony:bootstrap:build"
after "deploy:update", "deploy:cleanup"
after "deploy", "deploy:set_permissions"