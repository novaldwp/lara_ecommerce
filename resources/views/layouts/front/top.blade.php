<!-- Top bar Start -->
<div class="top-bar">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <i class="fa fa-envelope"></i>
                {{ \ProfileHelper::getProfile()->email }}
            </div>
            <div class="col-sm-6">
                <i class="fa fa-phone-alt"></i>
                {{ str_replace("0", "+(62) ", substr(chunk_split(\ProfileHelper::getProfile()->phone, 4, "-"), 0, -1)) }}
            </div>
        </div>
    </div>
</div>
<!-- Top bar End -->
