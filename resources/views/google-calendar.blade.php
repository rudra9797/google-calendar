<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Google Calendar</title>
</head>

<body>

    <div class="container-sm">
        <h1>Google Calendar</h1>
        @if ($message = Session::get('success'))
            <div class="alert alert-dismissible alert-success">
                <button class="close" type="button" data-dismiss="alert">×</button>
                {!! $message !!}
            </div>
        @endif

        <form method="post" action="{{ route('store') }}">
            <div class="row">
                <div class="mb-3">
                    <label class="form-label">Appointment Description</label>
                    <textarea class="form-control" name="description" rows="3"></textarea>
                    {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="row">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="date" class="form-control">
                    {!! $errors->first('date', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="col-md-4">
                    <label class="form-label">Start Time</label>
                    <input type="time" name="start_time" class="form-control">
                    {!! $errors->first('start_time', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="col-md-4">
                    <label class="form-label">End Time</label>
                    <input type="time" name="end_time" class="form-control">
                    {!! $errors->first('end_time', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Save</button>

        </form>


    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>


</body>

</html>
