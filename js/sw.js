let prefix = 'v1';
self.addEventListener("install", (e) => {
	self.skipWaiting();
})
self.addEventListener("fetch", (e) => {
	if (e.request.mode == "navigate") {
		e.respondWith(
			(async () => {
				try {
					const preloadResponse = await e.preloadResponse;
					if (preloadResponse) {
						return preloadResponse;
					}
					return await fetch(e.request);
				}catch(e) {
					return new Response("Bonjour les gens");
				}
			})()
		);
	}
});