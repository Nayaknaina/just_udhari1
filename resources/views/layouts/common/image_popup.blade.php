<div class="img_popup">
    <div class="overlay"></div>
    <div class="img-show">
      <span>X</span>
      <img src="">
    </div>
  </div>

<script>

    $(function () {
        "use strict";

        $(".popup img").click(function () {
            var $src = $(this).attr("src");
            $(".img_popup").fadeIn();
            $(".img-show img").attr("src", $src);
        });

        $("span, .overlay").click(function () {
            $(".img_popup").fadeOut();
        });

    });

</script>