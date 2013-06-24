# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL

set :application, "Pigalle"
set :domain,      "pigalle.com.ar"
set :deploy_to,   "httpdocs"
set :user,        "pigalle"
set :password,    "HUcszb5R"
set :app_path,    "app"
set :web_path,    "web"

role :web,        domain
role :app,        domain
role :db,         domain, :primary => true

set :scm,         :git
set :repository,  "https://github.com/songecko/piga-ecommerce.git"
#set :deploy_via,  :capifony_copy_local
set :deploy_via,  :copy
set :copy_dir, 	  "deploy_temp"

set :use_composer,   false
#set :use_composer_tmp, true
set :composer_options, "--verbose"
set :update_vendors, false

set :dump_assetic_assets, true

set :writable_dirs,     [app_path+"/cache", app_path+"/logs"]
set :webserver_user,    "www-data"
set :permission_method, :acl

set :shared_files,    ["app/config/parameters.yml", "web/.htaccess", "web/robots.txt"]
set :shared_children, [app_path+"/logs", web_path+"/media"]

set :model_manager, "doctrine"

set :use_sudo,    false

set :keep_releases, 3

before 'symfony:composer:update', 'symfony:copy_vendors'
 
namespace :gecko do
  desc "Customized finalize deploy"
  task :finalize_deploy do
  	capifony_pretty_print "--> Finalizing deploy"
    run "rm -rf #{release_path}/public/my_folder"
    run "mkdir -p #{shared_path}/my_folder"
    run "ln -nfs #{shared_path}/my_folder #{release_path}/public/my_folder"
  end
end

namespace :deploy do
	task :share_childs, :roles => :app, :except => { :no_release => true } do
		if shared_children
			capifony_pretty_print "--> Creating symlinks for shared directories"

			shared_children.each do |link|
				run "#{try_sudo} mkdir -p #{shared_path}/#{link}"
				run "#{try_sudo} sh -c 'if [ -d #{release_path}/#{link} ] ; then rm -rf #{release_path}/#{link}; fi'"
				run "#{try_sudo} ln -s #{shared_path}/#{link} #{release_path}/#{link}"
			end

			capifony_puts_ok
		end

		if shared_files
			capifony_pretty_print "--> Creating symlinks for shared files"

			shared_files.each do |link|
				link_dir = File.dirname("#{shared_path}/#{link}")
				run "#{try_sudo} mkdir -p #{link_dir}"
				run "#{try_sudo} touch #{shared_path}/#{link}"
				run "#{try_sudo} ln -s #{shared_path}/#{link} #{release_path}/#{link}"
			end

			capifony_puts_ok
		end
	end
end

namespace :symfony do
#	namespace :assets do
#		desc "Remember install assets manually"
#		task :install do
#			capifony_pretty_print "--> Remember install assets manually"
#		end
#	end
#	namespace :cache do
#		desc "Remember warmup the cache"
#		task :warmup do
#			capifony_pretty_print "--> Remember warmup the cache"
#		end
#	end
#	namespace :assetic do
#		desc "Remember dump assetic"
#		task :dump do
#			capifony_pretty_print "--> Remember dump assetic"
#		end
#	end
	
	namespace :vendor do
		desc "Copy vendors from previous release"
  			task :copy_from_previous, :except => { :no_release => true } do
    			if Capistrano::CLI.ui.agree("Do you want to copy last release vendor dir then do composer install ?: (y/N)")
      			capifony_pretty_print "--> Copying vendors from previous release"
	  
      			run "cp -a #{previous_release}/vendor #{latest_release}/"
      			capifony_puts_ok
    		end
  		end
	end
end

after "deploy:update_code", "symfony:copy_from_previous"
after "deploy:update", "deploy:cleanup"
after "deploy", "deploy:set_permissions"