@if (session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info'))
    {{-- This script controls toast notifications. Styling is handled by the toastr.js CSS file. --}}
    <script type="module">
        import toastr from 'toastr';

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
        };

        @if (session()->has('success'))
        toastr.success("{{ session('success') }}");
        @endif

        @if (session()->has('error'))
        toastr.error("{{ session('error') }}");
        @endif

        @if (session()->has('warning'))
        toastr.warning("{{ session('warning') }}");
        @endif

        @if (session()->has('info'))
        toastr.info("{{ session('info') }}");
        @endif
    </script>
@endif
