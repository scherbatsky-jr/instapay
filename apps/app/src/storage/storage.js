// later we can move this to some package
class AppStorage {
  constructor(storage, namespace) {
    this.namespace = namespace;
    this.storage = storage;
  }

  get length() {
    return this.storage.length;
  }

  clear() {
    for (let i = 0; i < this.storage.length; i++) {
      const key = this.storage.key(i);
      const regexp = new RegExp(`^${this.namespace}.+`, "i");

      if (key && regexp.test(key)) {
        this.storage.removeItem(key);
      }
    }
  }

  getItem(key) {
    return this.storage.getItem(`${this.namespace}${key}`);
  }

  key(index) {
    return this.storage.key(index);
  }

  removeItem(key) {
    this.storage.removeItem(`${this.namespace}${key}`);
  }

  setItem(key, value) {
    this.storage.setItem(`${this.namespace}${key}`, value);
  }
}

export default AppStorage;
