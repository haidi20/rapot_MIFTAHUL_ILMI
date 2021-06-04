<script type="text/javascript" src="{{asset('adminty/files/bower_components/jquery/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('adminty/files/bower_components/jquery-ui/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{asset('adminty/files/bower_components/popper.js/js/popper.min.js')}}"></script>
<script type="text/javascript" src="{{asset('adminty/files/bower_components/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- jquery slimscroll js -->
<script type="text/javascript" src="{{asset('adminty/files/bower_components/jquery-slimscroll/js/jquery.slimscroll.js')}}"></script>
<!-- modernizr js -->
<script type="text/javascript" src="{{asset('adminty/files/bower_components/modernizr/js/modernizr.js')}}"></script>

<!-- Tags js -->
<script type="text/javascript" src="{{asset('adminty/files/bower_components/bootstrap-tagsinput/js/bootstrap-tagsinput.js')}}"></script>
 <!-- Max-length js -->
 <script type="text/javascript" src="{{asset('adminty/files/bower_components/bootstrap-maxlength/js/bootstrap-maxlength.js')}}"></script>

<!-- Chart js -->
{{-- <script type="text/javascript" src="{{asset('adminty/files/bower_components/chart.js/js/Chart.js')}}"></script> --}}

<!-- amchart js -->
{{-- <script src="{{asset('adminty/files/assets/pages/widget/amchart/amcharts.js')}}"></script>
<script src="{{asset('adminty/files/assets/pages/widget/amchart/serial.js')}}"></script>
<script src="{{asset('adminty/files/assets/pages/widget/amchart/light.js')}}"></script> --}}
<script src="{{asset('adminty/files/assets/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script type="text/javascript" src="{{asset('adminty/files/assets/js/SmoothScroll.js')}}"></script>
<!-- custom js -->
<script src="{{asset('adminty/files/assets/js/pcoded.min.js')}}"></script>
<script src="{{asset('adminty/files/assets/js/vartical-layout.min.js')}}"></script>
<script type="text/javascript" src="{{asset('adminty/files/assets/js/script.js')}}"></script>

{{-- add library --}}
<script src="{{asset('bootstrap-table-master/dist/bootstrap-table.min.js')}}"></script>
<script src="{{asset('sweetalert2/dist/sweetalert2.min.js')}}"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'UA-23581568-13');

var substringMatcher = function(strs) {
    return function findMatches(q, cb) {
        var matches, substringRegex;

        // an array that will be populated with substring matches
        matches = [];

        // regex used to determine if a string contains the substring `q`
        substrRegex = new RegExp(q, 'i');

        // iterate through the pool of strings and for any string that
        // contains the substring `q`, add it to the `matches` array
        $.each(strs, function(i, str) {
            if (substrRegex.test(str)) {
                matches.push(str);
            }
        });

        cb(matches);
    };
};
</script>
