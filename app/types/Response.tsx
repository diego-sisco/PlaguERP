import { ApplicationMethodType } from "./ApplicationMethod";
import { DeviceType } from "./Device"
import { OrderType } from "./Order"
import { PestType } from "./Pest";
import { ProductType } from "./Product";
import { ServiceType } from "./Service";

export type ResponseType = {
   orders: OrderType[];
   services: ServiceType[];
   products: ProductType[];
   pests: PestType[];
   applicationMethods: ApplicationMethodType[];
   devices: DeviceType[];
}