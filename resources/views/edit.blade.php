<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Edit Updates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand" href="#"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                </ul>
                <form action="{{ route('logout') }}" method="POST" class="d-flex" role="search">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1> Welcome, {{ Auth::user()->name }}</h1>
    </div>
    <div class="container">
        @if (session('status'))
            <div class="alert alert-{{ session('class') }}">
                {{ session('status') }}
            </div>
        @endif
        <div class="mb-4">
            <div class="mb-4">
            </div>
            <h2></h2>
            <form action="{{ url('editUpdate/' . $emp->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="department">Select Department:</label>
                    <select id="department" name="department" class="form-control" required>
                        <option value=""></option>
                        <option value="qa" {{ $emp->department === 'qa' ? 'selected' : '' }}>Quality Assurance (QA)
                        </option>
                        <option value="glp" {{ $emp->department === 'glp' ? 'selected' : '' }}>Good Laboratory Practice (GLP)</option>
                        <option value="finance" {{ $emp->department === 'finance' ? 'selected' : '' }}>Finance and Accounting
                        </option>
                        <!-- Add more options as needed -->
                    </select>
                </div>
                <textarea id="textUpdate" name="textUpdate" rows="5" class="form-control"  placeholder="Add text update">{{$emp->textUpdate}}</textarea>
                <div class="mb-4">
                    <h2>Add Image</h2>
                    <input type="file" id="imageUpdate" name="imageUpdate" accept="image/*">
                    <img src="{{ asset('storage/'.$emp->imageUpdate) }}" width="70px" height="70px" alt="Image">
                </div>
                <div class="mb-4">
                    <h2>Add PDF</h2>
                    <input type="file" id="pdfUpdate" name="pdfUpdate" accept=".pdf">
                    <iframe src="{{ asset('storage/'.$emp->pdfUpdate) }}" width="70px" height="70px" alt="Image"></iframe>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Update</button>
            </form>
        </div>
    </div>

    <!-- Add Bootstrap JS and Popper.js scripts (for certain components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
