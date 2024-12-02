import { ServicePest } from "./ServicePest";
import { AnswerType } from "./Answer";

export type ReviewDevice = {
    order_id: number;
    device_id: number;
    questions: AnswerType[] | undefined;
    pests: AnswerType[] | undefined;
    is_checked: boolean;
    is_scanned: boolean;
    is_product_change: boolean;
    is_device_change: boolean;
    observs: string;
};