var CROP={};
var image=image||location.search.substr(1);
function crop(){
        CROP['filename']=image;
        window.opener.crop_save(CROP);
        window.close();
}
function init_crop() {
    window.resizeTo(document.getElementById('mofat').clientWidth+40,document.getElementById('mofat').clientHeight+130);
  var jcrop = $.Jcrop('#mofat',{
      aspectRatio: 1,
      setSelect: [10,10,300,350],
      minSize:[37,37]
  });

    $('#crop :button').click(function() {
        var selection = jcrop.tellSelect();
        CROP['x1']=selection.x;
        CROP['y1']=selection.y;
        CROP['x2']=selection.x2;
        CROP['y2']=selection.y2;
        CROP['w']=selection.w;
        CROP['h']=selection.h;
        //alert('selected size: ' + selection.w + 'x' + selection.h);
        crop();
    })
};
