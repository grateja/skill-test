class LoadingKeys {
    constructor() {
        this.keys = [];
    }

    add(item) {
        if (!this.keys.includes(item)) {
            this.keys.push(item);
        }
    }

    remove(item) {
        const index = this.keys.indexOf(item);
        if (index !== -1) {
            this.keys.splice(index, 1);
        }
    }

    clear() {
        this.keys = [];
    }

    hasAny(...items) {
        if (Array.isArray(items[0])) {
            items = items[0];
        }
        return items.some(item => this.keys.includes(item));
    }
}

export default LoadingKeys;
