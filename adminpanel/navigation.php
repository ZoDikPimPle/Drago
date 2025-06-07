<!-- navigation.php -->
<div class="navbar">
    <div class="container">
        <div class="navbar-nav">
            <div class="navbar-brand">
                <a href="profileA.php">
                    <img class="navbar-brand-png" src="../img/logo_main.png">
                </a>
            </div>
            <div class="navs" id="navs">
                <div class="navs-item" style="display: inline-block; margin-right: 10px;">
                    <a href="../adminpanel/redC.php">
                        <button class="btn txt-uppercase">Клиенты</button>
                    </a>
                </div>
                <div class="navs-item" style="display: inline-block; margin-right: 10px;">
                    <a href="../adminpanel/redN.php">
                        <button class="btn txt-uppercase">Фильмы</button>
                    </a>
                </div>
                <div class="navs-item" style="display: inline-block; margin-right: 10px;">
                    <a href="../adminpanel/redP.php">
                        <button class="btn txt-uppercase">Сеансы</button>
                    </a>
                </div>
                <div class="navs-item" style="display: inline-block; margin-right: 100px;">
                    <button class="btn txt-uppercase blue" onclick="window.history.back();">Назад</button>
                </div>
                <form method="post" class="raa" style="display: inline-block; margin-left: 20px;">
                    <div class="navs-item">
                        <input class="btn txt-uppercase shadow-sm" type="submit" name="exitA" value="Выход">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
