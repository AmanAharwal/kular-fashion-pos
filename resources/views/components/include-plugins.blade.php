@if($hasPlugin('datePicker'))
    @push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
    @endpush

    @push('scripts')
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script>
        $(function() {
            flatpickr('.date-picker', {
                dateFormat: "Y-m-d",
                allowInput: true,
                maxDate: "today"
            });
        });
    </script>
    @endpush
@endif

@if($hasPlugin('contentEditor'))
    @push('styles')
        <link href="{{ asset('assets/css/summernote/summernote-lite.min.css') }}" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/js/summernote/summernote-lite.min.js') }}"></script>
        <script>
            $(function(){
                $('#answer').summernote({
                    height: 400,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['table', ['table']],
                        ['insert', ['link', 'hr']],
                        ['view', ['fullscreen', 'codeview', 'help']],
                    ],
                });
            })
        </script>
    @endpush
@endif

@if($hasPlugin('dataTable'))
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" type="text/css" />
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    @endpush
@endif

@if($hasPlugin('jQueryValidate'))
    @push('scripts')
        <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    @endpush
@endif

@if($hasPlugin('chosen'))
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/libs/chosen/chosen.min.css') }}" />
    @endpush
    @push('scripts')
        <script src="{{ asset('assets/libs/chosen/chosen.jquery.min.js') }}"></script>
    @endpush
@endif

@if($hasPlugin('update-status'))
    @push('scripts')
    <script>
        $(function() {
            $('.update-status').change(function() {
                var status = $(this).prop('checked') ? 'Active' : 'Inactive'; // Send enum values
                var id = $(this).data('id');
                let statusUpdateApiEndpoint = $(this).data('endpoint');
                const toggleButton = $(this);
                swal({
                    title: "Are you sure?",
                    text: `You really want to ${status} this?`,
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: false,
                }, function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: statusUpdateApiEndpoint,
                            data: {
                                'status': status,
                                'id': id,
                                '_token': '{{ csrf_token() }}' 
                            },
                            success: function(response) {
                                if(response.success){
                                    swal({
                                        title: "Success!",
                                        text: response.message,
                                        type: "success",
                                        showConfirmButton: false
                                    }) 
    
                                    setTimeout(() => {
                                        location.reload();
                                    }, 2000);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error updating status:', error);
                            }
                        });
                    } else {
                        toggleButton.prop('checked', !toggleButton.prop('checked')); 
                    }
                });
            });
        });
    </script>
    @endpush
@endif

@if($hasPlugin('imagePreview'))
<script>
    function previewImage(event, key) {
        const file = event.target.files[0];
        const reader = new FileReader();
        
        reader.onload = function() {
            const preview = document.getElementById(`preview-${key}`);
            preview.src = reader.result;
            preview.hidden = false;
        }
        
        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>
@endif

@if($hasPlugin('colorPicker'))
    @push('styles')
        <link href="{{ asset('assets/libs/spectrum-colorpicker2/spectrum.min.css') }}" rel="stylesheet" type="text/css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    @endpush
    @push('scripts')
        <script src="{{ asset('assets/libs/spectrum-colorpicker2/spectrum.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    @endpush
@endif

@if($hasPlugin('country'))
<script>
    $(document).ready(function() {
        $('#country_id').change(function() {
            const countryId = $(this).val();
            const stateSelect = $('#state_id');
    
            stateSelect.html('<option value="" disabled selected>Select state</option>'); // Reset options
    
            if (countryId) {
                $.ajax({
                    url: `/get-states/${countryId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        data.states.forEach(function(state) {
                            stateSelect.append(new Option(state.name, state.id));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching states:', error);
                    }
                });
            }
        });
    });
</script>
@endif