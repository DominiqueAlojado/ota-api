<!DOCTYPE html>
<html>

<head>
    <title>New Job Posted</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; text-align: left;">

    <table style="width: 100%; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
        <tr>
            <td style="padding: 20px; text-align: left;">
                <h1 style="color: #333; margin: 0 0 15px 0;">{{ $job->job_title }}</h1>
                <p style="font-size: 16px; line-height: 1.5; color: #555; margin: 0;">
                    <strong>Company:</strong> {{ $job->company_name }} <br>
                    <strong>Email:</strong> {{ $job->company_email_address }} <br>
                    <strong>Address:</strong> {{ $job->company_address }} <br>
                    <strong>Type:</strong> {{ $job->job_type }} <br>
                    <strong>Seniority:</strong> {{ $job->seniority_level }} <br>
                    <strong>Schedule:</strong> {{ $job->work_schedule }} <br>
                    <strong>Work Experience:</strong> {{ $job->experience_range }} <br>
                    <strong>Keywords:</strong> {{ $job->keywords }} <br>
                    <strong>Description:</strong> {{ $job->job_description }}
                </p>

                <p style="margin-top: 20px;">
                    <a href="{{ env('APP_URL') . '/job-posting/approve/' . $job->id }}" target="_blank"
                        style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px; display: inline-block;">
                        ✅ Approve
                    </a>

                    <a href="{{ env('APP_URL') . '/job-posting/spam/' . $job->id }}" target="_blank"
                        style="background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                        🚫 Flag as Spam
                    </a>

                </p>

            </td>
        </tr>
    </table>

</body>

</html>