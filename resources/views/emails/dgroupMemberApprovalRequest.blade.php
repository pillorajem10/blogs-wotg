<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D-Group Member Approval Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #4CAF50;
            text-align: center;
        }
        p {
            line-height: 1.6;
        }
        .btn-approve {
            display: inline-block;
            background-color: #28a745;
            color: #ffffff;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }
        .btn-approve:hover {
            background-color: #218838;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #888888;
        }
        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .note {
            background-color: #f7f7f7;
            padding: 15px;
            border-left: 5px solid #4CAF50;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>D-Group Member Approval Request</h2>
        
        <p>Dear {{ $dgroupLeader->user_fname }},</p>

        <p>One of your members, <strong>{{ $memberEmail }}</strong>, has requested to join your D-Group. Please review their registration and approve them to finalize the process.</p>

        <a href="{{ route('dgroup.approve', ['email' => $memberEmail, 'token' => $approvalToken]) }}" class="btn-approve">
            Approve Member
        </a>

        <div class="note">
            <p><strong>Note:</strong> If you have any questions, feel free to reach out to the admin or contact support.</p>
        </div>

        <div class="footer">
            <p>Thank you for your time and leadership!</p>
            <p>Best regards, <br> WOTG Online</p>
        </div>
    </div>
</body>
</html>
