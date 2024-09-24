<!DOCTYPE html>
<html>
<head>
    <title>Send Email</title>
</head>
<body>
    <h1>Send Email Notification</h1>

   @if(session('message'))
    <div class="alert alert-success fade-message">
        <h2><p style="color: green;">{{ session('message') }}</p></h2>
    </div>
 @endif



     @if (session('success'))
        <h3><p style="color: green;">{{ session('success') }}</p></h3>
    @endif

    @if (session('error'))
        <h3><p style="color: red;">{{ session('error') }}</p></h3>
    @endif
    
    
    <form action="/send-email" method="POST">
        @csrf
        <label for="email">Recipient Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Send Email</button>
    </form>

    
    <h2>Failed Job Entries</h2>
    
    @if ($failedJobs->isEmpty())
        <p>No failed jobs.</p>
    @else
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Connection</th>
                    <th>Queue</th>
                    <th>Payload</th>
                    <th>Exception</th>
                    <th>Failed At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($failedJobs as $job)
                <tr>
                    <td>{{ $job->id }}</td>
                    <td>{{ $job->connection }}</td>
                    <td>{{ $job->queue }}</td>
                    <td>{{ Str::limit($job->payload, 50) }}</td>
                    <td>{{ Str::limit($job->exception, 130) }}</td>
                    <td>{{ $job->failed_at }}</td>
                     <td>
        <form action="{{ route('retry-job', $job->id) }}" method="POST">
    @csrf
    <button type="submit">Retry</button>
</form>

    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif


     <h2>Job Entries</h2>
    
    @if ($jobs->isEmpty())
        <p>No  jobs.</p>
    @else
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Queue</th>
                    <th>Payload</th>
                    <th>Attempts</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jobs as $job)
                <tr>
                    <td>{{ $job->id }}</td>
                    <td>{{ $job->queue }}</td>
                    <td>{{ Str::limit($job->payload, 140) }}</td>
                    <td>{{ Str::limit($job->attempts, 10) }}</td>
                    <td>
                        <form action="{{ route('queue.work') }}" method="POST">
    @csrf
    <button type="submit">Work/Run</button>
</form>
                    </td>
                    
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>

<style type="text/css">
    .fade-message {
    opacity: 1;
    transition: opacity 1s ease-in-out; 
}


.fade-message.hide {
    opacity: 0;
    pointer-events: none;
}

</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const messageElement = document.querySelector('.fade-message');
        
        if (messageElement) {
            
            setTimeout(() => {
                messageElement.classList.add('hide');
                
                
                setTimeout(() => {
                    messageElement.remove();
                }, 1000); 
            }, 3000); 
        }
    });
</script>

