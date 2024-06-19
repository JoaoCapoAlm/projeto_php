<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planeta Terra 3D com Animação</title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
        }

        canvas {
            display: block;
        }
    </style>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <nav style="position:absolute;">
        <ul class="main-nav" >

            <li><a href="../menu.php" class="btn-back">Home</a></li>
            <li><a href="../perfil.php" class="btn-perfil">Acessar Meu Perfil</a></li>
            <li><a href="../logout.php" class="logout-btn">Sair</a></li>

        </ul>
    </nav>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
    <script>
        const scene = new THREE.Scene();

        // Adicionar imagem de fundo
        const loaderTexture = new THREE.TextureLoader();
        loaderTexture.load('fundo.png', function(texture) {
            scene.background = texture;
        });

        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        camera.position.z = 1;

        const renderer = new THREE.WebGLRenderer();
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.body.appendChild(renderer.domElement);

        const controls = new THREE.OrbitControls(camera, renderer.domElement);

        const ambientLight = new THREE.AmbientLight(0x404040);
        scene.add(ambientLight);

        const directionalLight = new THREE.DirectionalLight(0xffffff, 4);
        directionalLight.position.set(-100, 100, 100);
        scene.add(directionalLight);

        const loader = new THREE.GLTFLoader();
        loader.load('beautiful_sexy_girl_model_5.glb', function(gltf) {
            const model = gltf.scene;
            scene.add(model);

            // Adicionar uma caixa ao redor do modelo
            // const boxGeometry = new THREE.BoxGeometry(1.2, 1.2, 1.2);
            // const boxMaterial = new THREE.MeshBasicMaterial({
            //     color: 0x00ff00,
            //     wireframe: true
            // });
            // const box = new THREE.Mesh(boxGeometry, boxMaterial);
            // scene.add(box);
            model.position.y -= 1.3;

            if (gltf.animations && gltf.animations.length > 0) {
                const mixer = new THREE.AnimationMixer(model);

                for (let i = 0; i < gltf.animations.length; i++) {
                    const animation = gltf.animations[i];
                    mixer.clipAction(animation).play();
                }

                function animate() {
                    requestAnimationFrame(animate);

                    mixer.update(-0.008);

                    // Ajustar o eixo de rotação
                    model.rotation.x += 0.0005;
                    model.rotation.y += 0.0005;

                    controls.update();
                    renderer.render(scene, camera);
                }

                animate();
            } else {
                console.warn('O modelo carregado não possui animações.');

                function animate() {
                    requestAnimationFrame(animate);

                    // Ajustar o eixo de rotação
                    // model.translate-100 x
                    model.rotation.y += 0.005;

                    controls.update();
                    renderer.render(scene, camera);
                }

                animate();
            }
        }, undefined, function(error) {
            console.error(error);
        });

        window.addEventListener('resize', () => {
            renderer.setSize(window.innerWidth, window.innerHeight);
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();

            directionalLight.position.set(0, window.innerHeight, 0);
        });
    </script>

</body>

</html>