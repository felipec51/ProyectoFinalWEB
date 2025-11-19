<?php

/**
 * Función para renderizar el componente "sidebar.php" de una serie/película.
 

 * @return void
 */




function rendersidebar(): void
{
?>
    <link rel="stylesheet" href="./styles/sidebar.css" />
    <div class="sidebar2 flex-row-center">
        <div class="container43">
            <div class="sidebarheader">
                <div class="appsidebar flex-row-center">
                    <div class="container44 flex-row-center">R</div>
                    <div class="container45">
                        <div class="text23 text-base">RewindCodeFilm</div>
                        <div class="text24 text-secundario">Admin Panel</div>
                    </div>
                </div>
            </div>
            <div class="sidebarcontent text-secundario">
                <div class="sidebargroup">
                    <div class="sidebargrouplabel padding-1 flex-row-center text-secundario">Navegación</div>
                    <div class="sidebarmenu">
                        <div class="sidebarmenuitem">
                            
                            <div class="slotclone flex-row-center">
                       
                                <img src="./imgs/icons/icono-10-(6).svg" class="icon18" alt="icono de inicio">
                                <a href="paneladmin.php" style="text-decoration:none; color:inherit;">
                                    <div class="appsidebar2 text-base">Inicio</div>
                                </a>
                            </div>
                        </div>
                        <div class="sidebarmenuitem2">
                            <div class="slotclone2 flex-row-center">
                                <img src="./imgs/icons/icono-10-(19).svg" class="icon18" alt="icono de películas">
                                <a href="adminpeliculas.php" style="text-decoration:none; color:inherit;">
                                    <div class="appsidebar3">Películas</div>
                                </a>
                            </div>
                        </div>
                        <div class="sidebarmenuitem3">
                            <div class="slotclone2 flex-row-center">
                                <img src="./imgs/icons/icono-10-(7).svg" class="icon18" alt="icono de tendencias">
                                <div class="appsidebar4">Genero</div>
                            </div>
                        </div>
                        <div class="sidebarmenuitem4">
                            <div class="slotclone2 flex-row-center">
                                <img src="./imgs/icons/icono-10-(4).svg" class="icon18" alt="icono de análisis">
                                <div class="appsidebar5">Actores</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sidebargroup2">
                    <div class="sidebargrouplabel2 padding-1 flex-row-center text-secundario">Gestión</div>
                    <div class="sidebarmenu2">
                        
                        <div class="sidebarmenuitem2">
                            <div class="slotclone2 flex-row-center">
                                
                                <img src="./imgs/icons/icono-10-(5).svg" class="icon18" alt="icono de usuarios">
                                <a href="adminusuarios.php" style="text-decoration:none; color:inherit;">
                                <div class="appsidebar7">Usuarios</div>
                                </a>
                            </div>
                        </div>
            
                       
                    </div>
                </div>
            </div>
            <div class="sidebarfooter">
                <div class="sidebarmenu3">
                    <div class="sidebarmenuitem5">
                        <div class="slotclone2 flex-row-center">
                            <img src="./imgs/icons/icono-10-(10).svg" class="icon18" alt="icono de configuración">
                            <div class="appsidebar9">Configuración</div>
                        </div>
                    </div>
                    <div class="sidebarmenuitem9">
                        <div class="slotclone9">
                            <div class="primitivespan2 flex-row-center">AD</div>
                            <div class="appsidebar10">
                                <div class="text25 flex-row-center text-base">Admin</div>
                                <div class="text26 flex-row-center text-secundario">admin@rewindcodefilm.com</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>