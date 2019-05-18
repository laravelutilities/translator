<?php

namespace LaravelUtility\Translator\Console\Commands;

use Illuminate\Console\Command;

class PrepareTranslator extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravelutilities:prepareTranslator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Laravel Utilities Prepare Translator File';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->merge();
    }
    
    private function merge()
    {
        foreach (config('translator.lang') as $lang) {
            $translator = [];
            foreach (glob(resource_path('lang/' . $lang) . '/*') as $file) {
                
                $filename = basename($file, '.php');
                if(in_array($filename, config('translator.exclude_files'))) continue;
                $translator[$filename] = require_once($file);
            }
            $content = "<?php \n return " . var_export($translator, true) . ";\n";
            file_put_contents(resource_path('lang/' . $lang . '/translator.php'),
                                            $content, LOCK_EX);
        }
    }
   

}
