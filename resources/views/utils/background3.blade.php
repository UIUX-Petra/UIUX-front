<style>
  #bg-wrap {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100vh;
      right: 0;
      bottom: 0;
      overflow: hidden;
      z-index: -1;
  }

  .imgPartial1 {
      position: fixed;
      top: 0;
      left: 0;
      width: 30%;
      height: 100%;
  }

  .imgPartial2 {
      position: fixed;
      top: 0;
      right: 0;
      width: 30%;
      height: 100%;
  }

  .imgPartial3 {
      position: fixed;
      top: 0;
      right: 0px;
      width: 100%;
      height: 100%;
  }
  .imgPartial3 img {
    width: 100%;
    height: 100%;
    /* object-fit: cover;
    opacity: 1;  */
}


  @media only screen and (max-width: 767px) {
      .imgPartial3 {
          width: 150%;
          height: 150%;
      }
  }
</style>
<div class="absolute inset-0 z-[-1]">
  <div id="bg-wrap" class="absolute inset-0">
      <div class="imgPartial3 opacity-100 object-cover">
          {{-- <img src="{{ asset('background\texture_4.jpg') }}" alt=""> --}}
      </div>
  </div>
</div>
