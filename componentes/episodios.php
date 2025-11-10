<?php

function renderepisodios(string $nombre_serie, array $episodios): void {
?>

<link rel="stylesheet" href="./styles/episodios.css" /></section>
<section class="episodios2" id="episodios-section">
            <h2 class="episodios3">Episodios</h2>
            <div class="btn-elegir-temp">
                <div class="btn-elegir-temp-child"></div>
                <span class="nombre-de-serie"><?php echo $nombre_serie; ?></span>
            </div>

            <div class="episode-card episode-1-pos">
                <div class="episode-card-bg"></div>
                <img class="episode-image-thumb" alt="Miniatura del Capítulo 1">
                <div class="info-capitulo">
                    <h3 class="episode-title-text">
                        <span>
                           <?php echo $nombre_serie; ?>:  &lt;&lt;Capitulo uno: <?php echo $episodios[0]; ?> &gt;&gt;
                        </span>
                    </h3>
                    <p class="descripsion-del-capitulo">Descripsion del capitulo <br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam hendrerit magna vel ante eleifend efficitur. Proin quis urna id mauris </p>
                </div>
            </div>
            <div class="episode-card episode-2-pos">
                <div class="episode-card-bg"></div>
                <img class="episode-image-thumb" alt="Miniatura del Capítulo 2">
                <div class="info-capitulo">
                    <h3 class="episode-title-text">
                        <span>
                            <?php echo $nombre_serie; ?>: &lt;&lt;Capitulo dos: <?php echo $episodios[1]; ?> &gt;&gt;
                        </span>
                    </h3>
                    <p class="descripsion-del-capitulo">Descripsion del capitulo <br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam hendrerit magna vel ante eleifend efficitur. Proin quis urna id mauris </p>
                </div>
            </div>
            <div class="episode-card episode-3-pos">
                <div class="episode-card-bg"></div>
                <iframe  class="episode-image-thumb" width="560" height="315" src="https://www.youtube.com/embed/DyvhuchMHY8?si=3pRu43BD7Do3Mefr&amp;controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                
                <div class="info-capitulo">
                    <h3 class="episode-title-text">
                        <span>
                            <?php echo $nombre_serie; ?>: &lt;&lt;Capitulo tres: <?php echo $episodios[2]; ?> &gt;&gt;
                        </span>
                    </h3>
                    <p class="descripsion-del-capitulo">Descripsion del capitulo <br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam hendrerit magna vel ante eleifend efficitur. Proin quis urna id mauris </p>
                </div>
            </div>
            <div class="episode-card episode-4-pos">
                <div class="episode-card-bg"></div>
                <img class="episode-image-thumb" alt="Miniatura del Capítulo 4">
                <div class="info-capitulo">
                    <h3 class="episode-title-text">
                        <span>
                            <?php echo $nombre_serie; ?>:  &lt;&lt;Capitulo cuatro: <?php echo $episodios[3]; ?> &gt;&gt;
                        </span>
                    </h3>
                    <p class="descripsion-del-capitulo">Descripsion del capitulo <br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam hendrerit magna vel ante eleifend efficitur. Proin quis urna id mauris </p>
                </div>
            </div>
        </section>
<?php
} 
?>