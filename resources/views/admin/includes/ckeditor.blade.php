@js(aApp('ckeditor/ckeditor.js'))
@js(aApp('ckfinder/ckfinder.js'))
<script>CKFinder.config({connectorPath: {!! json_encode(route('ckfinder_connector'))  !!} });</script>
