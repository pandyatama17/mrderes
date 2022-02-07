<!DOCTYPE html>
<html>
<head>
    <title>Ticket Request from {{ $mailVariables['requester_name'] }}</title>
</head>
<body>
    <h1>Ticket Request by {{ $mailVariables['requester_name'] }}</h1>
    <blockquote>
        Date/Time : <br>
        {{ $mailVariables['request_date'] }} <br>
        Zone :
        {{ $mailVariables['type'] }}<br><br> 
        Request notes: <br>
        {{ $mailVariables['request_notes'] }}
    </blockquote>
    <br>
    <hr>
    <a class="btn blue waves-effect waves-light green-text" href="{{ $mailVariables['ticket_url']['accept'] }}">
        Approve Request
    </a>
    | 
    <a class="btn blue waves-effect waves-light red-text" href="{{ $mailVariables['ticket_url']['reject'] }}">
        Reject Request
    </a>
    <p>Thank you</p>
</body>
</html>