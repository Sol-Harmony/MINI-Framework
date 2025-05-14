party.custom = {
    // Custom neuer Luftblasen Effekt
    bubbles: function (source = document.body, options = {}) {
        const defaults = {
            count    : 100,
            spread   : 100,
            size     : 1,
            speed    : 200,
            duration : 1,
            color    : ["#ff0000", "#00ff00", "#ffff00", "#00ffff"], // Red, Green, Yellow, Cyan
        };
        const opts                  = jQuery.extend({}, defaults, options);
        const bubble                = document.createElement("div");
        width = height              = 15;

        if (typeof opts.size == "number" && opts.size > 0 && opts.size < 5) {
            width = height          = 15 * opts.size;
        }

        bubble.style.width          = width  + 'px';
        bubble.style.height         = height + 'px';
        bubble.style.borderRadius   = "50%";
        bubble.style.background     = "radial-gradient(circle at 30% 30%, rgba(255,255,255,0.8), rgba(173,216,230,0.4))";

        var emitter = party.scene.current.createEmitter({
            emitterOptions: {
                loops         : 1,
                useGravity    : false,
                duration      : opts.duration,
                modules: [
                    new party.ModuleBuilder()
                        .drive("position")
                        .by((t) => new party.Vector(0, -opts.spread * t))
                        .build(),
                    new party.ModuleBuilder()
                        .drive("opacity")
                        .by((t) => 1 - t * (1 - (0.1 * opts.duration)))	
                        .build(),
                ],
            },
            emissionOptions: {
                rate            : 0,
                bursts          : [{ time: 0, count: opts.count }],
                sourceSampler   : party.sources.dynamicSource(source),
                angle           : party.variation.range(-110, -70),
                initialSpeed    : party.variation.range(opts.speed * 0.7, opts.speed * 1.3),
                initialColor: () =>
                    Array.isArray(opts.color)
                        ? party.Color.fromHex(opts.color[Math.floor(Math.random() * opts.color.length)])
                        : party.Color.fromHex(opts.color),
            },
            rendererOptions: {
                shapeFactory: () => bubble.cloneNode(true),
            },
        });
        opts.bubble = bubble;
        return jQuery.extend(emitter, {bubble : opts});
    },

    randomEffect: function (source = document.body) {
        var duration = 6;
        var count    = 200;
        var speed    = 500;
        var size     = 4;
        const effects = [
            () => party.confetti(source, {
                count    : count,
                speed    : speed * 2,  // kein Duration Parameter verfuegbar. Geschwindigkeit beeinflusst die Dauer
                size     : size,
            }),
            () => party.sparkles(source, {
                count    : count,
                speed    : speed,
                size     : size,
                lifetime : duration,
            }),
            () => party.custom.bubbles(source, {
                count    : count,
                speed    : speed,
                size     : size - 1,
                duration : duration,
            })
        ];

        const randomIndex = Math.floor(Math.random() * effects.length);
        return effects[randomIndex]();
    }
}
