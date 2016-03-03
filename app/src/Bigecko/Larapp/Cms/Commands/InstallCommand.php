<?php

namespace Bigecko\Larapp\Cms\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Illuminate\Support\Facades\DB;

class InstallCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'grcms:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '概瑞CMS安装';

    /**
     * Create a new command instance.
     *
     * @return \Bigecko\Larapp\Cms\Commands\InstallCommand
     */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        // 数据库迁移
        $this->call('migrate', array(
            '--path' => 'app/src/Bigecko/Larapp/Cms/migrations',
        ));

        if (!DB::table('users')->where('username', 'admin')->exists()) {
            // 创建默认后台管理员
            $this->call('create:user', array(
                '--role' => 'administrator',
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'password' => '111111',
            ));
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
