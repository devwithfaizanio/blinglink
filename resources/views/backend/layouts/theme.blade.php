<aside id="customizer" class="sidebar sidebar_customizer">

    <!-- Toggler -->
    <button class="sidebar-toggler" data-toggle="customizer"><span class="la la-gear animate-spin-slow"></span></button>

    <!-- Theme Customizer -->
    <div class="flex items-center justify-between h-20 p-4">
        <div>
            <h2>Theme Customizer</h2>
            <p>Customize & Preview</p>
        </div>
        <button type="button" class="close text-2xl leading-none hover:text-primary la la-times"
                data-toggle="customizer"></button>
    </div>
    <hr>
    <div class="overflow-y-auto">
        <div class="flex items-center justify-between p-4">
            <h5>Dark Mode</h5>
            <label class="switch switch_outlined">
                <input data-toggle="darkMode" type="checkbox">
                <span></span>
            </label>
        </div>
        <hr>
        <div class="flex items-center justify-between p-4">
            <h5>RTL</h5>
            <label class="switch switch_outlined">
                <input data-toggle="rtl" type="checkbox">
                <span></span>
            </label>
        </div>
        <hr>
        <div class="p-4">
            <h5>Menu Type</h5>
            <div class="mt-5">
                <label class="custom-radio">
                    <input type="radio" name="menuType" checked data-toggle="menu-type" data-value="default">
                    <span></span>
                    <span>Default</span>
                </label>
                <label class="custom-radio mt-2">
                    <input type="radio" name="menuType" data-toggle="menu-type" data-value="hidden">
                    <span></span>
                    <span>Hidden</span>
                </label>
                <label class="custom-radio mt-2">
                    <input type="radio" name="menuType" data-toggle="menu-type" data-value="icon-only">
                    <span></span>
                    <span>Icon Only</span>
                </label>
                <label class="custom-radio mt-2">
                    <input type="radio" name="menuType" data-toggle="menu-type" data-value="wide">
                    <span></span>
                    <span>Wide</span>
                </label>
            </div>
        </div>
    </div>
</aside>
