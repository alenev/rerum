<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }

        form {
            margin-bottom: 20px;
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

        <form action="{{ route('documents.upload') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Choose a JSON file:</label>
                <input type="file" name="file" class="form-control" accept=".json" required>
            </div>
            <button type="submit" class="btn btn-success">Upload</button>
        </form>

        @if (isset($indexBuilt) && isset($filename))
            <form action="{{ route('documents.search') }}" method="get">
                @csrf
                <div class="mb-3">
                    <label for="searchValue" class="form-label">Search:</label>
                    <input type="text" name="searchValue" class="form-control" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="useIndex" class="form-check-input">
                    <label class="form-check-label" for="useIndex">Use Index</label>
                </div>
                <input type="hidden" name="filename" value="{{ $filename }}">
                <button type="submit" class="btn btn-info">Search</button>
            </form>
        @endif
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
