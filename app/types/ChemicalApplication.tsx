import {Resource} from '../types/Resource';
import {ServicePest} from '../types/ServicePest';

export type ChemicalApplication = {
    order_id: number,
    resources: Resource[];
    pests: ServicePest[];
};