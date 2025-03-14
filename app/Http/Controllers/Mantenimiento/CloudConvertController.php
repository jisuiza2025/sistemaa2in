<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Mantenimiento\WordController;



use \CloudConvert\CloudConvert;
use \CloudConvert\Models\Job;
use \CloudConvert\Models\Task;

class CloudConvertController extends Controller
{	
	
    
   
	

	


	public function convert($filePath)
	{      

		
	
		try {
			
			$cloudconvert = new CloudConvert([
		    	'api_key' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMzdjZDlhYzNiNGFmMGI3NzVkYTExY2UwOWMzNGM0YTYyNzg1ZTYxM2RkZjA1YzJiMGZmODU3ZWU1YmYwZWRhYWRiNjZjOTVmYzM0M2FhOTciLCJpYXQiOjE2MzUyMDUyMDQuMDI2Mzk3LCJuYmYiOjE2MzUyMDUyMDQuMDI2Mzk5LCJleHAiOjQ3OTA4Nzg4MDMuOTk3NDA3LCJzdWIiOiI1NDI2MDYwMyIsInNjb3BlcyI6WyJ1c2VyLnJlYWQiLCJ1c2VyLndyaXRlIiwidGFzay5yZWFkIiwidGFzay53cml0ZSIsIndlYmhvb2sucmVhZCIsIndlYmhvb2sud3JpdGUiLCJwcmVzZXQucmVhZCIsInByZXNldC53cml0ZSJdfQ.sBZASjJ_DmpnbK3ZvnjBKCVrobDsxl2fWsCn7b-YiFLyefkeDweL8plyhWwUaeFPESFze1LxiGCg8JsbaizFWAzLvzFCaSedY3tNEKMbKkzcUyKmh2UCvwk_-RHrjq7iiGHBqPs2Xb3M1F8XHDcq0BoXeEkMeO1tVp2_DweMp0jQkxmicK9G4DQv_86CNwnungkElGYGxcy7MgIWH89P0BBJyXuy3fvf972bXifmlfQEwUfMSS_j99uphODYNsyWJUrIyXDsSmDLpsnNGCm14nTwH-uVU4N6Vtl1xX5Yw2__VLFhyce7-8ZG6mUr2M7S5xy8MUMSSau_W-gn0eQB00hN5maLsCoyYUQpAKyjfRgCnnMC8eySGAcyqz2mlyzPwPMPJHFz_lnpg54jC84e57JKBkF66dnjVnfjaz9qH87NdX1twgYKas2ncDHSWnjZ-pMbjXz8KjzKXnQHg7vn4BYwVf8nm4I53aABo_FbLHjUgb6YdsR-qkkcQv_Zbddu2OpYYRvxnVnFB5etvL7zwSpgQpQhoS8YSUexLsRxj4XyT4wWPjkrX2LMqHx1tcme7ja5Zs6hvNPxgz4SsHrMjHKuEwYeLqS0en1CVg2pTKdb6M4jNt3SO17BSFUD1U5_mjPfq3s-ouu24IiNC1XBCb9fkUKbMkw5l4j4Cbu33Wc',
		    	'sandbox' => false
			]);



			//$setUrl = 'http://200.1.180.89:8088/test/test.docx';

			$setUrl = \URL::to('/').$filePath;

			$job = (new Job())
			    ->addTask(
			        (new Task('import/url', 'import-my-file'))
			            ->set('url', $setUrl)
			           
			    )
			    ->addTask(
			        (new Task('convert', 'convert-my-file'))
			            ->set('input', 'import-my-file')
			            ->set('output_format', 'pdf')
			    )
			    ->addTask(
			        (new Task('export/url', 'export-my-file'))
			            ->set('input', 'convert-my-file')
			    );

			$cloudconvert->jobs()->create($job);

			$cloudconvert->jobs()->wait($job);

			$file = $job->getExportUrls()[0];

			$source = $cloudconvert->getHttpTransport()->download($file->url)->detach();
			$dest = fopen('cloudConvert/' . $file->filename, 'w');

			stream_copy_to_stream($source, $dest);


			//

			$out = public_path().'/cloudConvert/'.$file->filename;


			if(file_exists($out)){




				return $this->setRpta('ok','PDF generado existosamente via CloudConvert',$file->filename);

			}else{


				return $this->setRpta('error','No se pudo generar el archivo via CloudConvert');
			}



		} catch (\Exception $e) {
			
			return $this->setRpta('error','CloudConvert :'.$e->getMessage());

		}


		

	}
	

	



	

    
}

