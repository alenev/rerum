<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }

        label {
            margin-right: 10px;
        }

        input {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        @if (isset($filename))
            <form action="{{ route('documents.buildIndex', ['filename' => $filename]) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-primary">Build Index</button>
            </form>
        @endif

        <form action="{{ route('documents.rebuildIndex') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-warning">Rebuild Index</button>
        </form>

        <form action="{{ route('documents.search') }}" method="get">
            @csrf
            <div class="mb-3">
                <label for="searchValue" class="form-label">Search:</label>
                <input type="text" name="searchValue" class="form-control" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="useIndex" class="form-check-input">
                <label for="useIndex" class="form-check-label">Use Index</label>
            </div>

            @if (isset($filename))
                <input type="hidden" name="filename" value="{{ $filename }}">
            @endif

            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
