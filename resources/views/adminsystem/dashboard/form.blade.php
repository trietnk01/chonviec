@extends("adminsystem.master")
@section("content")
<form class="form-horizontal" role="form" name="frm">	
	{{ csrf_field() }}
	<?php echo "<pre>".print_r(__FILE__,true)."</pre>"; ?>
</form>	
@endsection()         

