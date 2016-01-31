$(function(){
    $.delete({
        categories : {
            category : {
                url : "/admin/module/slider/category/delete"
			},
			image : {
				url : "/admin/module/slider/image/delete"
			}
		}
	});
});