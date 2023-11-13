import AppStorage from "./storage";
import { config } from "@/config";

export default new AppStorage(localStorage, config.localStorageNamespace);
