<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

class EmailController extends Controller
{
    public function sendEmailNotification(Request $request)
    {
        $emailDetails = [
            'to' => $request->input('email'),  
            'subject' => 'Welcome to Our Platform',  
            'body' => 'Thank you for registering with us!'  
        ];

        
        SendEmailJob::dispatch($emailDetails); 

        $request->session()->flash('message', 'Email queued successfully!');

        return redirect()->back();
        
      
    }

    public function showForm()
    {
        return view('send-email');
    }

    public function retryJob($id)
{
    try {
        
        $failedJob = DB::table('failed_jobs')->where('id', $id)->first();

        if (!$failedJob) {
            return redirect()->back()->with('error', 'Job not found.');
        }

        
        \Log::info('Retrying job with payload: ', [json_decode($failedJob->payload)]);

        
        $payload = json_decode($failedJob->payload);

   
        Queue::later(now()->addSecond(), unserialize($payload->data->command));

        DB::table('failed_jobs')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Job retried successfully.');
    } catch (\Exception $e) {
        
        \Log::error('Failed to retry job: ' . $e->getMessage());

        return redirect()->back()->with('error', 'Failed to retry job: ' . $e->getMessage());
    }
}



    public function showFailedJobs()
    {
        
        $failedJobs = DB::table('failed_jobs')->get();

        return view('send-email', compact('failedJobs'));
    }


    public function work(Request $request)
    {
    try {
       
        Artisan::call('queue:work', [
            '--once' => true, 
        ]);

        return redirect()->back()->with('message', 'Queue worker executed successfully.');
    } catch (\Exception $e) {
        \Log::error('Failed to execute queue worker: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to execute queue worker.');
    }
}
}
