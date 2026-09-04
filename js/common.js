// 	The Kisok Change 18-8-2023 Start

var adminurl = origin + "/";

function do_ajax_call(data, onsuccess, url = "") {



	if (url != "") {

		var serviceurl = url;

	} else {

		var serviceurl = adminurl + "ajax/service/services.php";

	}

	var token = $('meta[name="token"]').attr('content');

	$.ajax({

		type: "POST",

		url: serviceurl,

		headers: {

			'X-CSRF-TOKEN': token

		},

		data: data,

		success: onsuccess,



		error: function (error) {

			console.log('error; ' + JSON.stringify(error));

		}



	});



}



function do_file_upload(onsuccess, fileinput, upfold, method) {



	var serviceurl = adminurl + "ajax/service/uploadfile.php";

	var token = $('meta[name="token"]').attr('content');

	var frmdata = new FormData();

	var files = $(fileinput)[0].files;

	if (files.length > 0) {

		frmdata.append('file', files[0]);

		frmdata.append('upfold', upfold);

		frmdata.append('method', method);

		frmdata.append('token', token);

		console.log(frmdata);

		$.ajax({

			url: serviceurl,

			type: 'post',

			data: frmdata,

			contentType: false,

			processData: false,

			success: onsuccess,

			error: function (error) {

				console.log('error; ' + JSON.stringify(error));

			}

		});



	} else {

		alert("Please select a file.");

	}



}



function confirmdelete(heading, question, onsuccess) {

	//alert("aaaaaa");

	$("#customModalLabel").html(heading);

	$(".modalques").html(question);

	/* $('#delete').find('.success').on('click', onsuccess);*/

	$('#deleteinfo').find('.success').unbind("click");

	$('#deleteinfo').find('.success').click(onsuccess);

}



function viewfile(page, data = {}, divId, oncontinue) {

	//alert("2");



	var post_data = data;

	var url = adminurl + "ajax/view/" + page + ".php";



	var onsuccess = function (data) {

		$(divId).html(data);

		oncontinue();

	}

	do_ajax_call(post_data, onsuccess, url);

}



/*function validateform($form) {

	 $($form).validate({ });

	 if($($form).find('.req-empty').length !== 0){

	 $(".req-empty").rules("add", { 

	 required:true

	 });

	 

	 }

	 if($($form).find('.req-number').length !== 0){

	 $(".req-number").rules("add", { 

	 required:true,

	 number:true

	 });

}

	 if($($form).find('.req-email').length !== 0){

	 $(".req-number").rules("add", { 

	 required:true,

	 email:true

	 });

}



  return $($form).valid();

};*/





function isLatLngInZone(latLngs, lat, lng) {

	vertices_y = new Array();

	vertices_x = new Array();

	longitude_x = lng;

	latitude_y = lat;

	latLngs = JSON.parse(latLngs);

	var r = 0;

	var i = 0;

	var j = 0;

	var c = 0;

	var point = 0;



	for (r = 0; r < latLngs.length; r++) {

		vertices_y.push(latLngs[r].lat);

		vertices_x.push(latLngs[r].lng);

	}

	points_polygon = vertices_x.length;

	for (i = 0, j = points_polygon; i < points_polygon; j = i++) {

		point = i;

		if (point == points_polygon)

			point = 0;

		if (((vertices_y[point] > latitude_y != (vertices_y[j] > latitude_y)) && (longitude_x < (vertices_x[j] - vertices_x[point]) * (latitude_y - vertices_y[point]) / (vertices_y[j] - vertices_y[point]) + vertices_x[point])))

			c = !c;

	}

	//alert(c);

	return c;

}



function closeform() {

	$("#deleteinfo").modal("hide");

	//$("#createedit").removeClass("cd-panel--is-visible");

	viewtable();



}

