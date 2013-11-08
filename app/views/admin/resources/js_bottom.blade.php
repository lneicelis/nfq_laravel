<!-- basic scripts -->

<!--[if !IE]> -->

<script type="text/javascript">
    window.jQuery || document.write("<script src='{{ URL::asset('assets/js/jquery-2.0.3.min.js') }}'>"+"<"+"/script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='{{ URL::asset('assets/js/jquery-1.10.2.min.js') }}'>"+"<"+"/script>");
</script>
<![endif]-->


<script src="{{ URL::asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/typeahead-bs2.min.js') }}"></script>

<!-- ace scripts -->

<script src="{{ URL::asset('assets/js/ace-elements.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/ace.min.js') }}"></script>