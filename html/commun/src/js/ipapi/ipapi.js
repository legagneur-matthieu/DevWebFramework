ip_api = function () {
    this.base_url = "http://ip-api.com";
    this.ipapi = [];
    this.save_ipapi = function () {
        localStorage.setItem("ipapi", JSON.stringify(this.ipapi));
    }
    this.load_ipapi = function () {
        if (localStorage.getItem("ipapi")) {
            this.ipapi = JSON.parse(localStorage.getItem("ipapi"));
        } else {
            this.ipapi = {
                "json": {"rl": 45, "tll": Date.now()},
                "batch": {"rl": 15, "tll": Date.now()}
            };
            this.save_ipapi();
        }
    }
    this.load_ipapi();
    this.json = function (ip) {
        return this.sendRequest(ip);
    }
    this.batch = function (ips) {
        return this.sendRequest(ips);
    }
    this.sendRequest = function (data) {
        if (is_array(data)) {
            endpoint = "batch";
            method = "POST";
            rdata = JSON.stringify(data);
            url = this.base_url + "/" + endpoint;
        } else {
            endpoint = "json";
            method = "GET";
            rdata = urlencode(data);
            url = this.base_url + "/" + endpoint + "/" + rdata;
        }
        option = {
            method,
            headers: {"Content-Type": "application/json"},
            body: endpoint === "batch" ? JSON.stringify(data) : undefined
        }
        refrech = (this.ipapi[endpoint].ttl < Date.now());
        if (this.ipapi[endpoint].rl > 0 || refrech) {
            fetch(url, option)
                    .then(response => response.json())
                    .then(data => {
                        if (refrech) {
                            this.ipapi[endpoint].rl = endpoint === "batch" ? 15 : 45;
                            this.ipapi[endpoint].tll = Date.now() + 60;
                        } else {
                            this.ipapi[endpoint].rl -= 1;
                        }
                        this.save_ipapi();
                        fetch(window.location + "&ipapi_data=" + JSON.stringify(data));
                    });
        }
    }
}
ipapi = new ip_api();