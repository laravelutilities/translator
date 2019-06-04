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
    protected $signature = 'laravelutilities:prepareTranslator'
            . '{--include= : Files to be included to create a transcript, if no value passed, will merge all the files except excluded ones}';

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
                if((!empty($this->option('include'))
                        && (!in_array($filename, explode(',', $this->option('include')))))
                        || in_array($filename, config('translator.exclude_files')))
                {
                   continue;
                }
                info("merged files======>" . $filename);
                $translator[$filename] = require_once($file);
            }
            $content = "<?php \n return " . var_export($translator, true) . ";\n";
            file_put_contents(resource_path('lang/' . $lang . '/translator.php'),
                                            $content, LOCK_EX);
        }
    }
   

}
