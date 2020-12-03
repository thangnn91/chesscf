<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;
use Illuminate\Support\Facades\Config;
use DateTime;

class BackupMysql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:backupdb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup mysql db to dropbox';

    /**
     * Create a new command instance.
     *
     * @return void
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
    public function handle()
    {
        //
        $database = Config::get('database.connections');
        $host = $database['mysql']['host'];
        $db = $database['mysql']['database'];
        $u = $database['mysql']['username'];
        $p =  $database['mysql']['password'];
        //$cmd = "C:\\xampp\\mysql\\bin\\mysqldump.exe -h $host -u $u -p$p $db > D:\\db_backup.sql";
        $folder = '/var/www/chesscf/storage/db_backup.sql';
        $cmd = "mysqldump -h $host -u $u -p$p $db > $folder";
        //dd($cmd);
        //$command = '"C:\xampp\mysql\bin\mysqldump.exe" '.$host ." -u".$u ." -p".$p." > your_web_site/$filename";
        exec($cmd);
        //exec("mysqldump -u $u -p$p $db | gzip > db_backup.sql.gz");

        $app = new \Kunnu\Dropbox\DropboxApp("og98xmsk17rlfcf", "xm2ewsfcnnnautn", 'fH5DYg8Wp3IAAAAAAAAAAaWfOM1-yFtPIokQqfSdzSNVX-LDLT50K85TYkhVbdxL');
        $dropbox = new Dropbox($app);
        $dropboxFile = new DropboxFile($folder);

        $dt = (new DateTime())->format("Y-m-d_H-i_s");
        try {
            $file = $dropbox->upload($dropboxFile, "/backups/bcp" . $dt, ['autorename' => true]);
            echo $file->getName();
        } catch (Exception $e) {
            echo $e;
        }
    }
}
