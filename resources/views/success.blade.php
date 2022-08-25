<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>{{ config('app.name') }} - QuickBooks connection successful</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

</head>
<body class="bg-light">

<div class="container">
    <div class="py-5 text-center">
        <h3 class="page-heading">QuickBooks linking successful</h3>
        {{--        <h2>Checkout form</h2>--}}
        <p class="lead">
            Your business <b>{{ $tenant->name }}</b> has linked to Quickbooks Online
            company <b>{{ $company->CompanyName }}</b> successfully.
        </p>

        <p class="lead">
            Please close this window, return to the previous window and reload to configure data synchronization.
        </p>
    </div>

    <footer class="my-5 pt-5 text-muted text-center text-small">
        <hr>
        <p class="mb-1">
            <a href="{{ config('app.url') }}" target="_blank">{{ version_commit() }}</a>
            &copy; {{ date('Y') }} {{ session('organization.company') }}
        </p>
    </footer>
</div>

</body>
</html>
