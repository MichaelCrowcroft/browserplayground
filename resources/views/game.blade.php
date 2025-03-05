<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>👾</text></svg>">
        <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

        <title>{{ config('app.name', 'Laravel') }}</title>

        <style>
            :root {
                --primary: #6366F1;
                --secondary: #A855F7;
                --background: #1E1B4B;
                --accent: #C4B5FD;
                --ui: #4F46E5;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                background-color: var(--background);
                font-family: 'Press Start 2P', cursive;
                color: var(--accent);
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                overflow: hidden;
                cursor: none;
            }

            .game-container {
                position: relative;
                width: 100vw;
                height: 100vh;
            }

            canvas {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }

            .game-ui {
                position: absolute;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                text-align: center;
                width: 100%;
                pointer-events: none;
                z-index: 1000;
            }

            h1 {
                font-size: 2rem;
                color: var(--primary);
                text-shadow: 0 0 10px var(--primary);
                margin-bottom: 1rem;
            }

            .instructions {
                font-family: 'VT323', monospace;
                font-size: 1.2rem;
                color: var(--accent);
            }

            #crosshair {
                position: absolute;
                top: 50%;
                left: 50%;
                width: 20px;
                height: 20px;
                transform: translate(-50%, -50%);
                pointer-events: none;
                z-index: 9999;
            }

            #crosshair::before,
            #crosshair::after {
                content: '';
                position: absolute;
                background: #FFF; /* Make crosshair visible against a dark background */
                box-shadow:
                    0 0 4px var(--primary),
                    0 0 8px rgba(255,255,255,0.5); /* Add a glow effect */

            }

            #crosshair::before {
                width: 2px;
                height: 16px;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            #crosshair::after {
                width: 16px;
                height: 2px;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
        </style>
    </head>
    <body>
        <div class="game-container">
            <canvas id="gameCanvas"></canvas>
            <div class="game-ui">
                <h1>⭐ DISCOVER AMAZING INDIE GAMES ⭐</h1>
                <p class="instructions">WASD to move, Mouse to look, Click to shoot</p>
            </div>
            <div id="crosshair"></div>
        </div>

        <script>
            // Player3D.js
            class Player {
                constructor(camera, scene) {
                    this.camera = camera;
                    this.scene = scene;
                    this.camera.rotation.order = 'YXZ';
                    this.moveSpeed = 0.2;
                    this.rotationSpeed = 0.002;
                    this.velocity = new THREE.Vector3();
                    this.height = 2;
                    this.jumpForce = 0.5;
                    this.gravity = 0.01;
                    this.isJumping = false;

                    this.setupControls();
                    this.setupPlayerModel();

                    // Camera initial position
                    this.camera.position.set(0, this.height, 0);
                }

                setupControls() {
                    // Mouse controls
                    document.addEventListener('mousemove', (e) => {
                        if (document.pointerLockElement) {
                            this.camera.rotation.y -= e.movementX * this.rotationSpeed;

                            // Limit vertical rotation
                            const verticalRotation = this.camera.rotation.x - e.movementY * this.rotationSpeed;
                            this.camera.rotation.x = Math.max(-Math.PI/2, Math.min(Math.PI/2, verticalRotation));
                        }
                    });

                    // Pointer lock
                    document.getElementById('gameCanvas').addEventListener('click', () => {
                        if (!document.pointerLockElement) {
                            document.getElementById('gameCanvas').requestPointerLock();
                        }
                    });
                }

                setupPlayerModel() {
                    // Gun model
                    const gunGeometry = new THREE.BoxGeometry(0.1, 0.1, 0.5);
                    const gunMaterial = new THREE.MeshPhongMaterial({ color: 0xC4B5FD });
                    this.gun = new THREE.Mesh(gunGeometry, gunMaterial);

                    // Position the gun in front of the camera
                    this.gun.position.set(0.2, -0.2, -0.5);
                    this.camera.add(this.gun);
                    this.scene.add(this.camera);
                }

                move(keys) {
                    // Reset velocity
                    this.velocity.x = 0;
                    this.velocity.z = 0;

                    // Get direction from camera rotation
                    const direction = new THREE.Vector3();
                    this.camera.getWorldDirection(direction);
                    direction.y = 0; // Keep movement on XZ plane
                    direction.normalize();

                    // Calculate right vector
                    const right = new THREE.Vector3(-direction.z, 0, direction.x);

                    // Apply movement based on keys
                    if (keys['KeyW']) {
                        this.velocity.add(direction.multiplyScalar(this.moveSpeed));
                    }
                    if (keys['KeyS']) {
                        this.velocity.add(direction.multiplyScalar(-this.moveSpeed));
                    }
                    if (keys['KeyA']) {
                        this.velocity.add(right.multiplyScalar(-this.moveSpeed));
                    }
                    if (keys['KeyD']) {
                        this.velocity.add(right.multiplyScalar(this.moveSpeed));
                    }
                    if (keys['Space'] && !this.isJumping) {
                        this.velocity.y = this.jumpForce;
                        this.isJumping = true;
                    }

                    // Apply gravity
                    this.velocity.y -= this.gravity;

                    // Update position
                    this.camera.position.add(this.velocity);

                    // Simple floor collision
                    if (this.camera.position.y < this.height) {
                        this.camera.position.y = this.height;
                        this.velocity.y = 0;
                        this.isJumping = false;
                    }

                    // Wall collision at boundaries
                    const bounds = 24;
                    if (Math.abs(this.camera.position.x) > bounds) {
                        this.camera.position.x = Math.sign(this.camera.position.x) * bounds;
                    }
                    if (Math.abs(this.camera.position.z) > bounds) {
                        this.camera.position.z = Math.sign(this.camera.position.z) * bounds;
                    }
                }

                shoot() {
                    this.gun.position.z = -0.4;
                    setTimeout(() => {
                        this.gun.position.z = -0.5;
                    }, 100);
                }
            }

            // Portal3D.js
            class Portal {
                constructor(position, game) {
                    this.position = position;
                    this.game = game;
                    this.textureLoader = new THREE.TextureLoader();
                    this.mesh = this.createPortalMesh();
                    this.glowEffect = this.createGlowEffect();
                    this.portalCenter = this.createPortalCenter();
                    this.nameLabel = this.createNameLabel();
                }

                createNameLabel() {
                    // Create canvas for text
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.width = 256;
                    canvas.height = 64;

                    // Draw text on canvas
                    context.fillStyle = 'rgba(0, 0, 0, 0.8)';
                    context.fillRect(0, 0, canvas.width, canvas.height);
                    context.font = '32px "Press Start 2P", monospace';
                    context.textAlign = 'center';
                    context.fillStyle = '#A855F7';
                    context.fillText(this.game.name || 'Unknown Game', canvas.width / 2, canvas.height / 2 + 10);

                    // Create texture from canvas
                    const texture = new THREE.CanvasTexture(canvas);

                    // Create label sprite
                    const material = new THREE.SpriteMaterial({ map: texture });
                    const sprite = new THREE.Sprite(material);

                    // Position label above portal
                    sprite.position.copy(this.position);
                    sprite.position.y += 1.5;

                    // Scale sprite
                    sprite.scale.set(2, 0.5, 1);

                    return sprite;
                }

                createPortalMesh() {
                    const geometry = new THREE.TorusGeometry(1, 0.1, 16, 32);
                    const material = new THREE.MeshPhongMaterial({
                        color: 0xA855F7,
                        emissive: 0xA855F7,
                        emissiveIntensity: 0.5
                    });
                    const mesh = new THREE.Mesh(geometry, material);
                    mesh.position.copy(this.position);
                    // No rotation for vertical orientation
                    return mesh;
                }

                createPortalCenter() {
                    // Create a circular plane for the portal image
                    const geometry = new THREE.CircleGeometry(0.9, 32);

                    // Load a placeholder image as texture
                    // You can replace this URL with your own image
                    this.textureLoader.crossOrigin = null
                    const texture = this.textureLoader.load('https://fls-9e511cc4-73e8-4419-b3b4-50d0f2a13cbe.laravel.cloud/' + this.game.image);

                    const material = new THREE.MeshBasicMaterial({
                        map: texture,
                        transparent: true,
                        opacity: 0.8,
                        side: THREE.DoubleSide
                    });

                    const portalCenter = new THREE.Mesh(geometry, material);
                    portalCenter.position.copy(this.position);
                    // Make it face forward (vertical orientation)

                    return portalCenter;
                }

                createGlowEffect() {
                    const geometry = new THREE.TorusGeometry(1.2, 0.15, 16, 32);
                    const material = new THREE.MeshPhongMaterial({
                        color: 0xA855F7,
                        transparent: true,
                        opacity: 0.3
                    });
                    const glow = new THREE.Mesh(geometry, material);
                    glow.position.copy(this.position);
                    // No rotation for vertical orientation
                    return glow;
                }

                update() {
                    this.mesh.rotation.z += 0.01;
                    this.glowEffect.rotation.z += 0.01;

                    // Pulse effect
                    const scale = 1 + Math.sin(Date.now() * 0.003) * 0.1;
                    this.glowEffect.scale.set(scale, scale, scale);

                    // Make sure the portal center faces the camera
                    this.portalCenter.lookAt(this.game.cameraPosition || new THREE.Vector3(0, 2, 0));

                    // Make sure the name label always faces the camera
                    if (this.game.cameraPosition) {
                        this.nameLabel.position.copy(this.position);
                        this.nameLabel.position.y += 1.5;
                    }
                }

                checkCollision(raycaster) {
                    const intersects = raycaster.intersectObject(this.mesh);
                    return intersects.length > 0;
                }
            }

            // Game3D.js
            class Game {
                constructor() {
                    this.setupThreeJS();
                    this.createEnvironment();
                    this.player = new Player(this.camera, this.scene);
                    this.portals = [];
                    this.bullets = [];
                    this.portalSpawnInterval = 5000;
                    this.lastPortalSpawn = 0;

                    // Bullet properties
                    this.bulletSpeed = 1;
                    this.bulletSize = 0.1;

                    this.keys = {};
                    this.setupEventListeners();
                    this.animate();
                }

                setupThreeJS() {
                    this.scene = new THREE.Scene();
                    this.camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
                    this.renderer = new THREE.WebGLRenderer({
                        canvas: document.getElementById('gameCanvas'),
                        antialias: true
                    });
                    this.renderer.setSize(window.innerWidth, window.innerHeight);
                    this.renderer.setClearColor(0x1E1B4B);

                    // Lighting
                    const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
                    this.scene.add(ambientLight);

                    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
                    directionalLight.position.set(0, 10, 0);
                    this.scene.add(directionalLight);
                }

                createBullet() {
                    const bulletGeometry = new THREE.SphereGeometry(this.bulletSize);
                    const bulletMaterial = new THREE.MeshPhongMaterial({
                        color: 0xC4B5FD,
                        emissive: 0xC4B5FD,
                        emissiveIntensity: 0.5
                    });
                    const bullet = new THREE.Mesh(bulletGeometry, bulletMaterial);

                    // Set bullet position and direction
                    bullet.position.copy(this.camera.position);
                    bullet.velocity = new THREE.Vector3(0, 0, -1)
                        .applyQuaternion(this.camera.quaternion)
                        .multiplyScalar(this.bulletSpeed);

                    this.scene.add(bullet);
                    this.bullets.push(bullet);

                    // Remove bullet after 2 seconds
                    setTimeout(() => {
                        this.scene.remove(bullet);
                        this.bullets = this.bullets.filter(b => b !== bullet);
                    }, 2000);
                }

                updateBullets() {
                    this.bullets.forEach(bullet => {
                        bullet.position.add(bullet.velocity);

                        // Check portal collisions for each bullet
                        this.portals.forEach(portal => {

                            const distance = bullet.position.distanceTo(portal.mesh.position);
                            const portalRadius = 1.1;

                            if (distance < portalRadius + this.bulletSize) {
                                window.open(portal.game.link, '_blank');
                                this.scene.remove(portal.mesh);
                                this.scene.remove(portal.glowEffect);
                                this.scene.remove(portal.portalCenter);
                                this.scene.remove(portal.nameLabel);
                                this.portals = this.portals.filter(p => p !== portal);

                                // Remove the bullet after collision
                                this.scene.remove(bullet);
                                this.bullets = this.bullets.filter(b => b !== bullet);
                            }
                        });
                    });
                }

                setupEventListeners() {
                    window.addEventListener('keydown', (e) => this.keys[e.code] = true);
                    window.addEventListener('keyup', (e) => this.keys[e.code] = false);
                    window.addEventListener('resize', () => this.handleResize());
                    window.addEventListener('click', () => {
                        if (document.pointerLockElement) {
                            this.createBullet();
                            this.player.shoot();
                        }
                    });
                }

                handleResize() {
                    this.camera.aspect = window.innerWidth / window.innerHeight;
                    this.camera.updateProjectionMatrix();
                    this.renderer.setSize(window.innerWidth, window.innerHeight);
                }

                spawnPortal() {
                    const x = (Math.random() - 0.5) * 40;
                    const z = (Math.random() - 0.5) * 40;
                    const position = new THREE.Vector3(x, 2, z);

                    const games = <?php echo json_encode($listings); ?>;
                    const game = games[Math.floor(Math.random() * games.length)];

                    const portal = new Portal(position, {
                        link: game.link,
                        name: game.name,
                        image: game.image,
                        cameraPosition: this.camera.position
                    });
                    this.scene.add(portal.mesh);
                    this.scene.add(portal.glowEffect);
                    this.scene.add(portal.portalCenter);
                    this.scene.add(portal.nameLabel);
                    this.portals.push(portal);

                    if (this.portals.length > 5) {
                        const oldPortal = this.portals.shift();
                        this.scene.remove(oldPortal.mesh);
                        this.scene.remove(oldPortal.glowEffect);
                        this.scene.remove(oldPortal.portalCenter);
                        this.scene.remove(oldPortal.nameLabel);
                    }
                }

                createEnvironment() {
                    // Floor
                    const floorGeometry = new THREE.PlaneGeometry(50, 50, 50, 50);
                    const floorMaterial = new THREE.MeshPhongMaterial({
                        color: 0x4F46E5,
                        wireframe: true
                    });
                    const floor = new THREE.Mesh(floorGeometry, floorMaterial);
                    floor.rotation.x = -Math.PI / 2;
                    this.scene.add(floor);

                    // Walls
                    const wallMaterial = new THREE.MeshPhongMaterial({
                        color: 0x6366F1,
                        wireframe: true
                    });

                    const walls = [
                        { pos: [0, 5, -25], scale: [50, 10, 1] },  // Back
                        { pos: [0, 5, 25], scale: [50, 10, 1] },   // Front
                        { pos: [-25, 5, 0], scale: [1, 10, 50] },  // Left
                        { pos: [25, 5, 0], scale: [1, 10, 50] }    // Right
                    ];

                    walls.forEach(wall => {
                        const wallGeometry = new THREE.BoxGeometry(...wall.scale);
                        const wallMesh = new THREE.Mesh(wallGeometry, wallMaterial);
                        wallMesh.position.set(...wall.pos);
                        this.scene.add(wallMesh);
                    });
                }

                update() {
                    this.player.move(this.keys);
                    this.updateBullets();

                    // Update camera position for portals to use
                    this.portals.forEach(portal => {
                        portal.game.cameraPosition = this.camera.position;
                    });

                    const currentTime = Date.now();
                    if (currentTime - this.lastPortalSpawn > this.portalSpawnInterval) {
                        this.spawnPortal();
                        this.lastPortalSpawn = currentTime;
                    }

                    this.portals.forEach(portal => portal.update());
                }

                animate() {
                    requestAnimationFrame(() => this.animate());
                    this.update();
                    this.renderer.render(this.scene, this.camera);
                }
            }

            // Initialize game on window load
            window.addEventListener('load', () => {
                new Game();
            });
        </script>
    </body>
</html>
