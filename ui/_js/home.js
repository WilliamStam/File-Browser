$(document).ready(function(){
	getData();
	
	$(document).on("click","*[data-folder]",function(e){
		e.preventDefault();
		var folder = $(this).attr("data-folder");
		if (folder!=$.bbq.getState("folder")){
		//	$.bbq.removeState("file");
		}
		$.bbq.pushState({"folder":folder});
		getData();
	});
	
	$(document).on("click","*[data-view]",function(e){
		e.preventDefault();
		$.bbq.pushState({"view":$(this).attr("data-view")})
		getData();
	});
	$(document).on("click","*[data-file]",function(e){
		e.preventDefault();
		$.bbq.pushState({"file":$(this).attr("data-file")})
		getData();
	});
	$(document).on("click",".refresh-btn",function(e){
		e.preventDefault();
		
		getData();
	});
	
	$(document).on("click","#upload-btn4",function(e){
		e.preventDefault();
		openProgress();
		
		$.doTimeout(4000,function(){
			
			closeProgress();
		})
		
		
	});
	
	
	
	
	$(document).on("click",".control-folder-new",function(){
		var path = $(this).closest("*[data-control-path]").attr("data-control-path");
		var folder = $(this).closest("*[data-control-folder]").attr("data-control-folder");
		
		_newFolder("Create a new folder",path,"")
		
	});
	
	$(document).on("click",".control-folder-delete",function(){
		var path = $(this).closest("*[data-control-path]").attr("data-control-path");
		var folder = $(this).closest("*[data-control-folder]").attr("data-control-folder");
		//$("#modal-popup").jqotesub($("#template-modal-controls-delete"),{"type":"folder","label":folder}).modal("show")
		
		
		_delete("folder","Are you sure you want to delete this folder?",path,folder)
		
	});
	$(document).on("click",".control-folder-rename",function(){
		var path = $(this).closest("*[data-control-path]").attr("data-control-path");
		var folder = $(this).closest("*[data-control-folder]").attr("data-control-folder");
		//$("#modal-popup").jqotesub($("#template-modal-controls"),{"type":"folder","label":folder}).modal("show")
		
		_rename("folder","Rename the current folder",path,folder)
		
	
		
		
		
	});
	$(document).on("click",".control-file-delete",function(){
		var path = $(this).closest("*[data-control-path]").attr("data-control-path");
		var file = $(this).closest("*[data-control-file]").attr("data-control-file");
		//$("#modal-popup").jqotesub($("#template-modal-controls-delete"),{"type":"file","label":file}).modal("show")
		
		_delete("file","Are you sure you want to delete this file?",path,file)
		
	});
	$(document).on("click",".control-file-rename",function(){
		var path = $(this).closest("*[data-control-path]").attr("data-control-path");
		var file = $(this).closest("*[data-control-file]").attr("data-control-file");
		//$("#modal-popup").jqotesub($("#template-modal-controls"),{"type":"file","label":file}).modal("show")
		
		_rename("file","Rename the current file",path,file)
	});
	
	
	
	
	
	
	
	
	
	init_uploader();
	
	panel_block_top();
});
var uploader = {};
function init_uploader(){
	var filesUploaded = 1;
	uploader = new plupload.Uploader({
		runtimes : 'html5,flash,silverlight,html4',
		
		browse_button : 'upload-btn', // you can pass in id...
		container: document.getElementById('upload-btn-container'), // ... or DOM Element itself
		
		url : "upload.php",
		multiple_queues:true,
		multi_selection: true,
		chunk_size: '5mb',
		
		
		/*
		 filters : {
		 max_file_size : '10mb',
		 mime_types: [
		 {title : "Image files", extensions : "jpg,gif,png"},
		 {title : "Zip files", extensions : "zip"}
		 ]
		 },
		 */
		// Flash settings
		flash_swf_url : '../../vendor/moxiecode/plupload/js/Moxie.swf',
		
		// Silverlight settings
		silverlight_xap_url : '../../vendor/moxiecode/plupload/js/Moxie.xap',
		
		
		init: {
			PostInit: function(up) {
				up.bind('FilesAdded', function(up, files) {
					var i = 0;
					$("#upload-progress .progress-bar").css("width",0).html("Starting");
					plupload.each(files, function(file) {
						setTimeout(function () { up.start(); }, 100);
					});
					
				});
			},
			FileUploaded: function (up, file) {
				filesUploaded = filesUploaded + 1;
			},
			BeforeUpload: function (up, file) {
				up.settings.url = "upload.php?folder="+$.bbq.getState("folder");
				openProgress();
			},
			UploadComplete: function (up, file) {
				closeProgress();
				up.destroy();
				init_uploader();
				toastr["success"]((filesUploaded-1)+" file(s) successfully uploaded", "Success");
			},
			
			UploadProgress: function(up, file) {
				$("#upload-progress .progress-bar").css("width",file.percent+"%").html(file.percent+"%");
				if (up.files.length>1){
					$("#upload-progress-filename").html('<span class="label">Uploading <strong>'+filesUploaded+'</strong> of <strong>'+up.files.length+'</strong>:</span> <span class="filename">'+file.name+'</span>');
				} else {
					$("#upload-progress-filename").html('<span class="label">Uploading:</span> <span class="filename">'+file.name+'</span>');
				}
				
				
			},
			
			Error: function(up, err) {
				toastr["error"]("There was an error uploading the file(s)", "Error");
				closeProgress();
			}
		}
	});
	
	uploader.init();
	
	
}

function _newFolder(title,path,_name,_new){
	bootbox.prompt({
		size: 'small',
		value:_new||_name,
		title: ""+title,
		callback: function(result){
			if (result != null && result != "") {
				$.post("action.php?class=folder&method=_new",{"new":result,"path":path},function(response){
					if (response.error){
						toastr["error"]("There was an error creating the folder", "Error");
						_newFolder("Folder creation failed: "+response.error,path,_name,result)
					} else {
						toastr["success"]("Folder creating was successful", "Success");
						getData();
					}
				})
			}
		}
	})
}
function _rename(section,title,path,_name,_new){
	bootbox.prompt({
		size: 'small',
		value: _new||_name,
		title: "<i class='fa fa-"+section+"'></i> "+title,
		callback: function(result){
			if (result != null && result != _name) {
				$.post("action.php?class="+section+"&method=_rename",{"name":_name,"new":result,"path":path},function(response){
					if (response.error){
						toastr["error"]("There was an error re-nameing the "+section, "Error");
						_rename(section,section+" rename failed: "+response.error,path,_name,result)
					} else {
						toastr["success"]("Re-nameing the "+section+ " was successful", "Success");
						if (section=="file"){
							$.bbq.pushState({"file":result});
						}
						getData();
					}
				})
			}
		}
	})
}

function _delete(section,title,path,_name){
	var subTitle = "";
	if (section=="folder"){
		subTitle = '<div class="alert alert-danger text-center">(All files and folders inside the current folder will be deleted) </div>'
		
	}
	
	bootbox.confirm({
		size: 'small',
		message: "<h3>"+title+" </h3><div class='alert alert-info text-center' style='margin-top:30px;'><strong>"+_name+"</strong></div><p class='bootbox-icon'><i class='fa fa-"+section+"'></i></p>"+subTitle,
		callback: function(result){
			if (result){
				$.post("action.php?class="+section+"&method=_delete",{"name":_name,"path":path},function(response){
					if (response.error){
						toastr["error"]("There was an error deleting the "+section, "Error");
						_delete(section,section+" delete failed: "+response.error,path,_name)
					} else {
						toastr["success"]("Deleting the "+section+ " was successful", "Success");
						if (section=="file"){
							$.bbq.pushState({"file":result});
						}
						getData();
					}
				})
			}
			
		}
	});
}





function getData(){
	var folder = $.bbq.getState("folder");
	var view = $.bbq.getState("view");
	var file = $.bbq.getState("file");
	
	$.getData("/data.php", {"folder": folder, "view": view, "file": file}, function (data) {
		//console.log(data.folders)
		
		
		
		$("#tree-pane").jqotesub($("#template-folders"), data.folders);
		$("#details-pane").jqotesub($("#template-folder-"+data.settings.view), data.folder);
		
		
		$("#details-pane").find("ul.folder").prepend($.jqote($("#template-li-parent-folder-thumbs"),data.controls));
		$("#details-pane").find("tbody.folder").prepend($.jqote($("#template-li-parent-folder-list"),data.controls));
		$("#breadcrumb-links").jqotesub($("#template-breadcrumbs"),data.controls);
		
		
		
		$("#tree-pane a[data-folder]").each(function(){
			var $this = $(this);
			if ($this.attr("data-folder")==data.controls.current){
				$this.addClass("active").find(".fa-folder-o").removeClass("fa-folder-o").addClass("fa-folder-open-o");
			}
		});
		
		$("li.active a[data-view]").closest("li").removeClass("active");
		$("a[data-view]").each(function(){
			var $this = $(this);
			if ($this.attr("data-view")==data.settings.view){
				$this.closest("li").addClass("active");
			}	
			
		});
		
		$("*[data-file]").each(function(){
			var $this = $(this);
			if ($this.attr("data-file")==data.settings.file){
				$this.addClass("active");
			}	
			
		})
		
		panel_block_top();
		
		
		selected(data);
		
	},"page-data")
}

function selected(data){
	
	$('*[data-file="'+data.settings.file+'"]').each(function(){
		var $this = $(this);
			$this.addClass("selected");
	})
	$('*[data-folder="'+data.settings.folder+'"]').each(function(){
		var $this = $(this);
			$this.addClass("selected");
	})
	
	
	$("#control-area").jqotesub($("#template-controls"), data);
	
	
}
function panel_block_top(opening){
	var navHeight = $(".navbar-fixed-top > .container-fluid").outerHeight();
	if (opening){
		navHeight = $(".navbar-fixed-top").outerHeight();
	}
	
	$(".panel-block").stop(true,true).animate({"top":navHeight},500);
	
}
function openProgress(){
	$("#upload-progress").fadeIn(500);
	panel_block_top(true)
}
function closeProgress(){
	$("#upload-progress").fadeOut(500,function(){
		$("#upload-progress .progress-bar").css("width",0);
	});
	panel_block_top();
	
	getData()
}