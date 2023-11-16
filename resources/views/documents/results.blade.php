<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 10px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 5px;
        }

    </style>
</head>

<body>
    <div class="container">
        <h1>Search Results</h1>

        <p>Use Index: {{ $useIndex ? 'Yes' : 'No' }}</p>

        @if (isset($result["result"]) && count($result["result"]) > 0)
            <ul>
                @foreach ($result["result"] as $document)
                    <li>{{ json_encode($document) }}</li>
                @endforeach
            </ul>
        @else
            <p>No results found.</p>
        @endif

        <p>Comparison Count: {{ $result["comparisonCount"] }}</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
