import { ServicePest } from "./ServicePest";
import { AnswerType } from "./Answer";

export type ReviewDevice = {
    order_id: number;
    device_id: number;
    questions: AnswerType[];
    pests: AnswerType[];
    is_checked: boolean;
    is_scanned: boolean;
    product_change: boolean;
    observs: string;
};