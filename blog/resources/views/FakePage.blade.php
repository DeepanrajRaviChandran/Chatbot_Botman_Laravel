<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

</head>

<body>

    <div class="container mt-5">
        <h2>Payment Page</h2>

        <form id="myForm">
            @csrf
            <div class="form-group">
                <label for="exampleField">Information</label>
                <input type="text" class="form-control" id="exampleField" name="exampleField"
                    placeholder="Enter something">
            </div>
            <button type="button" class="btn btn-primary" id="submitBtn">Submit Payment</button>
        </form>
    </div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Payment Success</h5>
                </div>
                <div class="modal-body" id="successModalBody">
                    <div>Thanks for the Payment</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="successModalCloseBtn"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            $("#submitBtn").click(function () {
                $("#successModal").modal("show");
            });
        });

        $(function () {
                $('#successModalCloseBtn').click(function () {
                window.top.close();
            });
        });

        $(document).ready(function () {
            // Extract debtorId from the URL
            var debtorId = window.location.pathname.split('/').pop();

            $('#successModalCloseBtn').on('click', function () {
                $.ajax({
                    type: 'POST',
                    url: '/api/thanksForPayment/' + debtorId,
                });
            });
        });

    </script>

</body>

</html>
