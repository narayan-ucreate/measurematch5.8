<div class="pagination">
    @if(isset($_REQUEST['orderBy']) && !empty($_REQUEST['orderBy']))
        {!! $service_packages->appends(['orderBy' => $_REQUEST['orderBy']])->links() !!}
    @else 
        {!! $service_packages->links() !!}
    @endif 
</div>