@extends('admin.dashboard')
@section('admin')
    <style>
        .filter-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            /* Optional: Add some spacing between the label and the input box */
        }

        #employee-filter {
            width: 200px;
            /* Adjust the width to your desired size */
        }
    </style>
    <div class="page-content">

        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">Welcome to {{ $deprt }}</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
                    <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle><i
                            data-feather="calendar" class="text-primary"></i></span>
                    <input type="text" class="form-control bg-transparent border-primary" placeholder="Select date"
                        data-input>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-12 col-xl-12 stretch-card">
                <div class="row flex-grow-1">
                    <div class="col-md-4 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Today Updates</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $current }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Total Updates</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $employees->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- row -->


        <div class="row">
            <div class="col-lg-7 col-xl-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0">Updates</h6>
                        </div>
                        <div class="table-responsive">
                            <input type="text" id="employee-filter" class="form-control"
                                placeholder="Search Employee Name">

                            <table class="table table-hover mb-6">
                                <thead>
                                    <tr>
                                        <th class="pt-0">#</th>
                                        <th class="pt-0">Employee Name</th>
                                        <th class="pt-0">Text Update</th>
                                        <th class="pt-0">PDF Update</th>
                                        <th class="pt-0">Image Update</th>
                                        <th class="pt-0 center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->textUpdate }}</td>
                                            <td>
                                                @if (!empty($data->pdfUpdate))
                                                    <iframe src="{{ asset('storage/'.$data->pdfUpdate) }}" width="300"
                                                        alt="no pdf" height="200" frameborder="0"></iframe>
                                                @else
                                                    No PDF
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($data->imageUpdate))
                                                    <iframe src="{{ asset('storage/'.$data->imageUpdate) }}" width="300"
                                                        height="200" frameborder="0"></iframe>
                                                @else
                                                    No Image
                                                @endif
                                            </td>
                                            <td>
                                                <!-- Add button with 'Add' text and an icon -->
                                                <form action="{{ url('admin-add/' . $data->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit">
                                                        <i class="fas fa-plus"></i> Add
                                                    </button>
                                                </form>

                                                <!-- Edit button wrapped in a form with 'Edit' text and an icon -->
                                                <form action="{{ url('edit-update/' . $data->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit">
                                                        <i class="fas fa-pencil-alt"></i> Edit
                                                    </button>
                                                </form>

                                                <!-- Delete button with an icon -->
                                                <form action="{{ url('delete/' . $data->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- Add more rows as needed -->
                                </tbody>
                            </table>
                        </div>

                        <script>
                            document.getElementById("employee-filter").addEventListener("keyup", function() {
                                const filterValue = this.value.toLowerCase();
                                const rows = document.querySelectorAll(".table tbody tr");

                                rows.forEach(row => {
                                    const employeeName = row.querySelector("td:nth-child(2)").textContent.toLowerCase();
                                    if (employeeName.includes(filterValue)) {
                                        row.style.display = "";
                                    } else {
                                        row.style.display = "none";
                                    }
                                });
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div> <!-- row -->

    </div>
@endsection
