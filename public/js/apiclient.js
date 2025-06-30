class ApiClient {
    #sendRequestEvent() {
        window.addEventListener('sendRequest', (event) => {
            const url = event.detail.url;
            const body = event.detail.body;

            let bodyUrlEncoded = "";

            for (const key in body) {
                const value = body[key];

                if (bodyUrlEncoded != "") bodyUrlEncoded += `&`;

                bodyUrlEncoded += `${key}=${value}`;
            }

            fetch(
                url,
                {
                    method: 'post',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: bodyUrlEncoded,
                    credentials: 'same-origin'
                }
            )
                .then((res) => {
                    if (res.ok) {
                        console.log('Ok');
                    } else {
                        console.log('No Ok');
                    }
                })
                .catch((e) => {
                    console.log('Error: ', e);
                    return;
                });
        });
    }

    constructor() {
        // Define the event for when a request is sent
        this.#sendRequestEvent();
    }
}

new ApiClient();