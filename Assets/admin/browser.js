$(function(){
    $.delete({
        categories : {
            category : {
                url : "/admin/module/slider/category/delete.ajax"
			},
			image : {
				url : "/admin/module/slider/image/delete.ajax"
			}
		}
	});
});
